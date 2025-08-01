<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserType;
use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProducerProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'UserTypeSeeder']);
        $this->artisan('db:seed', ['--class' => 'CategorieSeeder']);
        $this->artisan('db:seed', ['--class' => 'ProductImageSeeder']);

        Storage::fake('public');
    }

    /** @test */
    public function test_producer_can_create_product()
    {
        $producerType = UserType::where('title', 'Producteur')->first();
        $producer = User::factory()->create(['user_type_id' => $producerType->id]);
        $category = Categorie::factory()->create();

        $productData = [
            'name' => 'Test Product',
            'description' => 'This is a test product description.',
            'categorie_id' => $category->id,
            'quantity' => 10.5,
            'price' => 100,
            'measure' => 'kg',
            'isbio' => true,
        ];

        $response = $this->actingAs($producer, 'sanctum')->postJson('/api/v1/producer/products', $productData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'name',
                    'description',
                    'categorie',
                    'producer',
                    'quantity',
                    'price',
                    'measure',
                    'isbio',
                    'images',
                ]
            ]);

        $this->assertDatabaseHas('produits', [
            'name' => 'Test Product',
            'producer_id' => $producer->id,
            'categorie_id' => $category->id,
        ]);
    }

    /** @test */
    public function test_producer_cannot_create_product_with_invalid_data()
    {
        $producerType = UserType::where('title', 'Producteur')->first();
        $producer = User::factory()->create(['user_type_id' => $producerType->id]);

        $response = $this->actingAs($producer, 'sanctum')->postJson('/api/v1/producer/products', [
            'name' => '', // Invalid: empty name
            'categorie_id' => 999, // Invalid: non-existent category
            'quantity' => -5, // Invalid: negative quantity
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'categorie_id', 'quantity']);
    }

    /** @test */
    public function test_non_producer_cannot_create_product()
    {
        $clientType = UserType::where('title', 'Client')->first();
        $client = User::factory()->create(['user_type_id' => $clientType->id]);
        $category = Categorie::factory()->create();

        $productData = [
            'name' => 'Test Product',
            'description' => 'This is a test product description.',
            'categorie_id' => $category->id,
            'quantity' => 10.5,
            'price' => 100,
            'measure' => 'kg',
            'isbio' => true,
        ];

        $response = $this->actingAs($client, 'sanctum')->postJson('/api/v1/producer/products', $productData);

        $response->assertStatus(403);
    }

    /** @test */
    public function test_producer_can_update_product()
    {
        $producerType = UserType::where('title', 'Producteur')->first();
        $producer = User::factory()->create(['user_type_id' => $producerType->id]);
        $category = Categorie::factory()->create();
        $product = Produit::factory()->create(['producer_id' => $producer->id, 'categorie_id' => $category->id]);

        $updatedData = [
            'name' => 'Updated Product Name',
            'price' => 150,
            'description' => 'Updated description.',
        ];

        $response = $this->actingAs($producer, 'sanctum')->putJson("/api/v1/producer/products/{$product->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Produit mis à jour avec succès'])
            ->assertJsonFragment(['name' => 'Updated Product Name']);

        $this->assertDatabaseHas('produits', [
            'id' => $product->id,
            'name' => 'Updated Product Name',
            'price' => 150,
            'description' => 'Updated description.',
        ]);
    }

    /** @test */
    public function test_producer_cannot_update_another_producers_product()
    {
        $producerType = UserType::where('title', 'Producteur')->first();
        $producerA = User::factory()->create(['user_type_id' => $producerType->id]);
        $producerB = User::factory()->create(['user_type_id' => $producerType->id]);
        $category = Categorie::factory()->create();

        $productB = Produit::factory()->create(['producer_id' => $producerB->id, 'categorie_id' => $category->id]);

        $updatedData = [
            'name' => 'Attempted Update',
        ];

        $response = $this->actingAs($producerA, 'sanctum')->putJson("/api/v1/producer/products/{$productB->id}", $updatedData);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('produits', [
            'id' => $productB->id,
            'name' => 'Attempted Update',
        ]);
    }

    /** @test */
    public function test_producer_can_delete_product()
    {
        $producerType = UserType::where('title', 'Producteur')->first();
        $producer = User::factory()->create(['user_type_id' => $producerType->id]);
        $category = Categorie::factory()->create();
        $product = Produit::factory()->create(['producer_id' => $producer->id, 'categorie_id' => $category->id]);

        // Add an image to the product to test image deletion
        $imageFile = UploadedFile::fake()->image('product_image.jpg', 100, 100);
        $path = $imageFile->store('produits', 'public');
        $product->images()->create([
            'name' => $imageFile->getClientOriginalName(),
            'type' => $imageFile->getClientMimeType(),
            'path' => $path
        ]);

        $response = $this->actingAs($producer, 'sanctum')->deleteJson("/api/v1/producer/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Produit supprimé avec succès']);

        $this->assertDatabaseMissing('produits', ['id' => $product->id]);
        $this->assertDatabaseMissing('product_images', ['product_id' => $product->id]);
        Storage::disk('public')->assertMissing($path);
    }

    /** @test */
    public function test_producer_can_add_product_images()
    {
        $producerType = UserType::where('title', 'Producteur')->first();
        $producer = User::factory()->create(['user_type_id' => $producerType->id]);
        $category = Categorie::factory()->create();
        $product = Produit::factory()->create(['producer_id' => $producer->id, 'categorie_id' => $category->id]);

        $imageFile1 = UploadedFile::fake()->image('product_image_1.jpg', 100, 100);
        $imageFile2 = UploadedFile::fake()->image('product_image_2.png', 100, 100);

        $response = $this->actingAs($producer, 'sanctum')->postJson("/api/v1/producer/products/{$product->id}/images", [
            'images' => [$imageFile1, $imageFile2],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => ['id', 'name', 'path']
                ]
            ]);

        $this->assertDatabaseHas('product_images', ['product_id' => $product->id, 'name' => 'product_image_1.jpg']);
        $this->assertDatabaseHas('product_images', ['product_id' => $product->id, 'name' => 'product_image_2.png']);
        Storage::disk('public')->assertExists('produits/' . $imageFile1->hashName());
        Storage::disk('public')->assertExists('produits/' . $imageFile2->hashName());
    }

    /** @test */
    public function test_producer_can_delete_product_image()
    {
        $producerType = UserType::where('title', 'Producteur')->first();
        $producer = User::factory()->create(['user_type_id' => $producerType->id]);
        $category = Categorie::factory()->create();
        $product = Produit::factory()->create(['producer_id' => $producer->id, 'categorie_id' => $category->id]);

        $imageFile = UploadedFile::fake()->image('image_to_delete.jpg', 100, 100);
        $path = $imageFile->store('produits', 'public');
        $productImage = $product->images()->create([
            'name' => $imageFile->getClientOriginalName(),
            'type' => $imageFile->getClientMimeType(),
            'path' => $path
        ]);

        $response = $this->actingAs($producer, 'sanctum')->deleteJson("/api/v1/producer/products/{$product->id}/images/{$productImage->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Image supprimée avec succès']);

        $this->assertDatabaseMissing('product_images', ['id' => $productImage->id]);
        Storage::disk('public')->assertMissing($path);
    }
}
