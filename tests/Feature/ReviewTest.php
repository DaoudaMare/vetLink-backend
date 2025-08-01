<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserType;
use App\Models\Produit;
use App\Models\Review;
use App\Models\Commande;
use App\Models\Categorie;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'UserTypeSeeder']);
        $this->artisan('db:seed', ['--class' => 'CategorieSeeder']);
    }

    /** @test */
    public function test_customer_can_submit_review_for_product()
    {
        $customerType = UserType::where('title', 'Client')->first();
        $customer = User::factory()->create(['user_type_id' => $customerType->id]);

        $producerType = UserType::where('title', 'Producteur')->first();
        $producer = User::factory()->create(['user_type_id' => $producerType->id]);

        $category = Categorie::factory()->create();
        $product = Produit::factory()->create(['producer_id' => $producer->id, 'categorie_id' => $category->id]);

        // Create a completed order for the customer and product
        $order = Commande::create([
            'num' => 'ORD-' . uniqid(),
            'customer_id' => $customer->id,
            'total_price' => $product->price,
            'status' => 2, // Assuming 2 is a 'completed' status
            'delivery_status' => 1,
            'payment' => 1,
        ]);
        $order->produits()->attach($product->id, ['quantity' => 1]);

        $reviewData = [
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'Excellent product!',
        ];

        $response = $this->actingAs($customer, 'sanctum')->postJson('/api/v1/reviews', $reviewData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => ['id', 'product_id', 'user_id', 'rating', 'comment']
            ]);

        $this->assertDatabaseHas('reviews', [
            'product_id' => $product->id,
            'user_id' => $customer->id,
            'rating' => 5,
            'comment' => 'Excellent product!',
        ]);
    }

    /** @test */
    public function test_customer_cannot_submit_review_for_non_existent_product()
    {
        $customerType = UserType::where('title', 'Client')->first();
        $customer = User::factory()->create(['user_type_id' => $customerType->id]);

        $reviewData = [
            'product_id' => 999, // Non-existent product ID
            'rating' => 5,
            'comment' => 'This should fail.',
        ];

        $response = $this->actingAs($customer, 'sanctum')->postJson('/api/v1/reviews', $reviewData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['product_id']);
    }

    /** @test */
    public function test_customer_can_update_their_review()
    {
        $customerType = UserType::where('title', 'Client')->first();
        $customer = User::factory()->create(['user_type_id' => $customerType->id]);

        $producerType = UserType::where('title', 'Producteur')->first();
        $producer = User::factory()->create(['user_type_id' => $producerType->id]);

        $category = Categorie::factory()->create();
        $product = Produit::factory()->create(['producer_id' => $producer->id, 'categorie_id' => $category->id]);

        // Create a completed order for the customer and product
        $order = Commande::create([
            'num' => 'ORD-' . uniqid(),
            'customer_id' => $customer->id,
            'total_price' => $product->price,
            'status' => 2, // Assuming 2 is a 'completed' status
            'delivery_status' => 1,
            'payment' => 1,
        ]);
        $order->produits()->attach($product->id, ['quantity' => 1]);

        $review = Review::create([
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'rating' => 3,
            'comment' => 'Initial comment.',
        ]);

        $updatedData = [
            'rating' => 4,
            'comment' => 'Updated comment.',
        ];

        $response = $this->actingAs($customer, 'sanctum')->putJson("/api/v1/reviews/{$review->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Évaluation mise à jour avec succès'])
            ->assertJsonFragment(['rating' => 4])
            ->assertJsonFragment(['comment' => 'Updated comment.']);

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'rating' => 4,
            'comment' => 'Updated comment.',
        ]);
    }

    /** @test */
    public function test_customer_cannot_update_another_users_review()
    {
        $customerType = UserType::where('title', 'Client')->first();
        $customerA = User::factory()->create(['user_type_id' => $customerType->id]);
        $customerB = User::factory()->create(['user_type_id' => $customerType->id]);

        $producerType = UserType::where('title', 'Producteur')->first();
        $producer = User::factory()->create(['user_type_id' => $producerType->id]);

        $category = Categorie::factory()->create();
        $product = Produit::factory()->create(['producer_id' => $producer->id, 'categorie_id' => $category->id]);

        // Create a completed order for customer B and product
        $orderB = Commande::create([
            'num' => 'ORD-' . uniqid(),
            'customer_id' => $customerB->id,
            'total_price' => $product->price,
            'status' => 2, // Assuming 2 is a 'completed' status
            'delivery_status' => 1,
            'payment' => 1,
        ]);
        $orderB->produits()->attach($product->id, ['quantity' => 1]);

        $reviewB = Review::create([
            'user_id' => $customerB->id,
            'product_id' => $product->id,
            'rating' => 3,
            'comment' => 'Review by B.',
        ]);

        $updatedData = [
            'rating' => 5,
            'comment' => 'Attempted update by A.',
        ];

        $response = $this->actingAs($customerA, 'sanctum')->putJson("/api/v1/reviews/{$reviewB->id}", $updatedData);

        $response->assertStatus(403);

        $this->assertDatabaseHas('reviews', [
            'id' => $reviewB->id,
            'rating' => 3,
            'comment' => 'Review by B.',
        ]);
    }

    /** @test */
    public function test_customer_can_delete_their_review()
    {
        $customerType = UserType::where('title', 'Client')->first();
        $customer = User::factory()->create(['user_type_id' => $customerType->id]);

        $producerType = UserType::where('title', 'Producteur')->first();
        $producer = User::factory()->create(['user_type_id' => $producerType->id]);

        $category = Categorie::factory()->create();
        $product = Produit::factory()->create(['producer_id' => $producer->id, 'categorie_id' => $category->id]);

        // Create a completed order for the customer and product
        $order = Commande::create([
            'num' => 'ORD-' . uniqid(),
            'customer_id' => $customer->id,
            'total_price' => $product->price,
            'status' => 2, // Assuming 2 is a 'completed' status
            'delivery_status' => 1,
            'payment' => 1,
        ]);
        $order->produits()->attach($product->id, ['quantity' => 1]);

        $review = Review::create([
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'rating' => 3,
            'comment' => 'Review to delete.',
        ]);

        $response = $this->actingAs($customer, 'sanctum')->deleteJson("/api/v1/reviews/{$review->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Évaluation supprimée avec succès']);

        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }

    /** @test */
    public function test_customer_cannot_delete_another_users_review()
    {
        $customerType = UserType::where('title', 'Client')->first();
        $customerA = User::factory()->create(['user_type_id' => $customerType->id]);
        $customerB = User::factory()->create(['user_type_id' => $customerType->id]);

        $producerType = UserType::where('title', 'Producteur')->first();
        $producer = User::factory()->create(['user_type_id' => $producerType->id]);

        $category = Categorie::factory()->create();
        $product = Produit::factory()->create(['producer_id' => $producer->id, 'categorie_id' => $category->id]);

        // Create a completed order for customer B and product
        $orderB = Commande::create([
            'num' => 'ORD-' . uniqid(),
            'customer_id' => $customerB->id,
            'total_price' => $product->price,
            'status' => 2, // Assuming 2 is a 'completed' status
            'delivery_status' => 1,
            'payment' => 1,
        ]);
        $orderB->produits()->attach($product->id, ['quantity' => 1]);

        $reviewB = Review::create([
            'user_id' => $customerB->id,
            'product_id' => $product->id,
            'rating' => 3,
            'comment' => 'Review by B.',
        ]);

        $response = $this->actingAs($customerA, 'sanctum')->deleteJson("/api/v1/reviews/{$reviewB->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('reviews', ['id' => $reviewB->id]);
    }

    /** @test */
    public function test_anyone_can_view_product_reviews()
    {
        $customerType = UserType::where('title', 'Client')->first();
        $customer = User::factory()->create(['user_type_id' => $customerType->id]);

        $producerType = UserType::where('title', 'Producteur')->first();
        $producer = User::factory()->create(['user_type_id' => $producerType->id]);

        $category = Categorie::factory()->create();
        $product = Produit::factory()->create(['producer_id' => $producer->id, 'categorie_id' => $category->id]);

        // Create a completed order for the customer and product
        $order = Commande::create([
            'num' => 'ORD-' . uniqid(),
            'customer_id' => $customer->id,
            'total_price' => $product->price,
            'status' => 2, // Assuming 2 is a 'completed' status
            'delivery_status' => 1,
            'payment' => 1,
        ]);
        $order->produits()->attach($product->id, ['quantity' => 1]);

        $review = Review::create([
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'rating' => 4,
            'comment' => 'Great product!',
        ]);

        $response = $this->getJson("/api/v1/reviews/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => ['id', 'product_id', 'user_id', 'rating', 'comment']
                ]
            ])
            ->assertJsonFragment(['comment' => 'Great product!']);
    }
}
