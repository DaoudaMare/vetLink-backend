<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserType;
use App\Models\Produit;
use App\Models\Commande;
use App\Models\Categorie;
use App\Models\Status;

class CustomerOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'UserTypeSeeder']);
        $this->artisan('db:seed', ['--class' => 'CategorieSeeder']);
        $this->artisan('db:seed', ['--class' => 'StatusSeeder']);

        // Set a fixed time for testing date-based queries
        \Carbon\Carbon::setTestNow(\Carbon\Carbon::create(2025, 7, 31, 10, 0, 0));
        config(['app.timezone' => 'UTC']);
        date_default_timezone_set('UTC');
    }

    /** @test */
    public function test_customer_can_view_their_order_history()
    {
        $customerType = UserType::where('title', 'Client')->first();
        $customer = User::factory()->create(['user_type_id' => $customerType->id]);

        $producerType = UserType::where('title', 'Producteur')->first();
        $producer = User::factory()->create(['user_type_id' => $producerType->id]);

        $category = Categorie::factory()->create();
        $product = Produit::factory()->create(['producer_id' => $producer->id, 'categorie_id' => $category->id]);

        // Create some orders for the customer
        $order1 = Commande::create([
            'num' => 'CMD-001',
            'customer_id' => $customer->id,
            'total_price' => 100,
            'status' => 1,
            'delivery_status' => 1,
            'payment' => 1,
        ]);
        $order1->produits()->attach($product->id, ['quantity' => 1]);

        $order2 = Commande::create([
            'num' => 'CMD-' . uniqid(),
            'customer_id' => $customer->id,
            'total_price' => 200,
            'status' => 1,
            'delivery_status' => 1,
            'payment' => 1,
        ]);
        $order2->produits()->attach($product->id, ['quantity' => 2]);

        $response = $this->actingAs($customer, 'sanctum')->getJson('/api/v1/customer/orders');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => ['id', 'num', 'total_price']
                ]
            ])
            ->assertJsonFragment(['num' => $order1->num])
            ->assertJsonFragment(['num' => $order2->num]);
    }

    /** @test */
    public function test_customer_can_view_details_of_their_order()
    {
        $customerType = UserType::where('title', 'Client')->first();
        $customer = User::factory()->create(['user_type_id' => $customerType->id]);

        $producerType = UserType::where('title', 'Producteur')->first();
        $producer = User::factory()->create(['user_type_id' => $producerType->id]);

        $category = Categorie::factory()->create();
        $product = Produit::factory()->create(['producer_id' => $producer->id, 'categorie_id' => $category->id]);

        $order = Commande::create([
            'num' => 'CMD-' . uniqid(),
            'customer_id' => $customer->id,
            'total_price' => 100,
            'status' => 1,
            'delivery_status' => 1,
            'payment' => 1,
        ]);
        $order->produits()->attach($product->id, ['quantity' => 1]);

        $response = $this->actingAs($customer, 'sanctum')->getJson("/api/v1/customer/orders/{$order->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => ['id', 'num', 'total_price', 'products']
            ])
            ->assertJsonFragment(['num' => $order->num]);
    }

    /** @test */
    public function test_customer_can_view_todays_orders()
    {
        $customerType = UserType::where('title', 'Client')->first();
        $customer = User::factory()->create(['user_type_id' => $customerType->id]);

        $producerType = UserType::where('title', 'Producteur')->first();
        $producer = User::factory()->create(['user_type_id' => $producerType->id]);

        $category = Categorie::factory()->create();
        $product = Produit::factory()->create(['producer_id' => $producer->id, 'categorie_id' => $category->id]);

        // Create an order for today
        \Carbon\Carbon::setTestNow(\Carbon\Carbon::create(2025, 7, 31, 10, 0, 0));
        $orderToday = Commande::create([
            'num' => 'CMD-TODAY',
            'customer_id' => $customer->id,
            'total_price' => 50,
            'status' => 1,
            'delivery_status' => 1,
            'payment' => 1,
        ]);
        $orderToday->produits()->attach($product->id, ['quantity' => 1]);

        // Create an order for yesterday
        \Carbon\Carbon::setTestNow(\Carbon\Carbon::create(2025, 7, 30, 10, 0, 0));
        $orderYesterday = Commande::create([
            'num' => 'CMD-YESTERDAY',
            'customer_id' => $customer->id,
            'total_price' => 75,
            'status' => 1,
            'delivery_status' => 1,
            'payment' => 1,
        ]);
        $orderYesterday->produits()->attach($product->id, ['quantity' => 1]);

        // Reset time to today for the API call
        \Carbon\Carbon::setTestNow(\Carbon\Carbon::create(2025, 7, 31, 10, 0, 0));

        $response = $this->actingAs($customer, 'sanctum')->getJson('/api/v1/customer/orders/today');

        $response->assertStatus(200)
            ->assertJsonFragment(['num' => 'CMD-TODAY'])
            ->assertJsonMissing(['num' => 'CMD-YESTERDAY']);
    }

    /** @test */
    public function test_customer_can_view_current_orders()
    {
        $customerType = UserType::where('title', 'Client')->first();
        $customer = User::factory()->create(['user_type_id' => $customerType->id]);

        $producerType = UserType::where('title', 'Producteur')->first();
        $producer = User::factory()->create(['user_type_id' => $producerType->id]);

        $category = Categorie::factory()->create();
        $product = Produit::factory()->create(['producer_id' => $producer->id, 'categorie_id' => $category->id]);

        // Create a pending order
        $pendingStatus = Status::where('name', 'En attente')->first();
        $orderPending = Commande::create([
            'num' => 'CMD-PENDING',
            'customer_id' => $customer->id,
            'total_price' => 100,
            'status' => $pendingStatus->id,
            'delivery_status' => 1,
            'payment' => 0,
        ]);
        $orderPending->produits()->attach($product->id, ['quantity' => 1]);

        // Create a completed order
        $completedStatus = Status::where('name', 'Terminé')->first();
        $orderCompleted = Commande::create([
            'num' => 'CMD-COMPLETED',
            'customer_id' => $customer->id,
            'total_price' => 200,
            'status' => $completedStatus->id,
            'delivery_status' => 1,
            'payment' => 1,
        ]);
        $orderCompleted->produits()->attach($product->id, ['quantity' => 1]);

        $response = $this->actingAs($customer, 'sanctum')->getJson('/api/v1/customer/orders/current');

        $response->assertStatus(200)
            ->assertJsonFragment(['num' => 'CMD-PENDING'])
            ->assertJsonMissing(['num' => 'CMD-COMPLETED']);
    }

    /** @test */
    public function test_customer_can_cancel_their_order()
    {
        $customerType = UserType::where('title', 'Client')->first();
        $customer = User::factory()->create(['user_type_id' => $customerType->id]);

        $producerType = UserType::where('title', 'Producteur')->first();
        $producer = User::factory()->create(['user_type_id' => $producerType->id]);

        $category = Categorie::factory()->create();
        $product = Produit::factory()->create(['producer_id' => $producer->id, 'categorie_id' => $category->id]);

        $order = Commande::create([
            'num' => 'CMD-TO-CANCEL',
            'customer_id' => $customer->id,
            'total_price' => 100,
            'status' => 1, // Initial status
            'delivery_status' => 1,
            'payment' => 0,
        ]);
        $order->produits()->attach($product->id, ['quantity' => 1]);

        $response = $this->actingAs($customer, 'sanctum')->putJson("/api/v1/customer/orders/{$order->id}/cancel");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Commande annulée avec succès']);

        $cancelledStatus = Status::where('name', 'Annulé')->first();
        $this->assertDatabaseHas('commandes', [
            'id' => $order->id,
            'status' => $cancelledStatus->id,
        ]);

        // Verify product quantity is incremented back
        $this->assertEquals($product->quantity + 1, $product->fresh()->quantity);
    }

    /** @test */
    public function test_customer_cannot_cancel_another_customers_order()
    {
        $customerType = UserType::where('title', 'Client')->first();
        $customerA = User::factory()->create(['user_type_id' => $customerType->id]);
        $customerB = User::factory()->create(['user_type_id' => $customerType->id]);

        $producerType = UserType::where('title', 'Producteur')->first();
        $producer = User::factory()->create(['user_type_id' => $producerType->id]);

        $category = Categorie::factory()->create();
        $product = Produit::factory()->create(['producer_id' => $producer->id, 'categorie_id' => $category->id]);

        $orderB = Commande::create([
            'num' => 'CMD-B-' . uniqid(),
            'customer_id' => $customerB->id,
            'total_price' => 100,
            'status' => 1,
            'delivery_status' => 1,
            'payment' => 0,
        ]);
        $orderB->produits()->attach($product->id, ['quantity' => 1]);

        $response = $this->actingAs($customerA, 'sanctum')->putJson("/api/v1/customer/orders/{$orderB->id}/cancel");

        $response->assertStatus(403);

        // Verify order status is not changed
        $this->assertDatabaseHas('commandes', [
            'id' => $orderB->id,
            'status' => 1,
        ]);
    }
}
