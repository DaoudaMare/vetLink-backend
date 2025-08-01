<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserType;
use App\Models\Produit;
use App\Models\Categorie;

class OrderCreationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_customer_can_create_an_order_with_multiple_products()
    {
        // 1. Setup
        $customerType = UserType::factory()->create(['title' => 'Client']);
        $customer = User::factory()->create(['user_type_id' => $customerType->id]);

        $category = Categorie::factory()->create();
        $product1 = Produit::factory()->create(['categorie_id' => $category->id, 'price' => 10, 'quantity' => 20]);
        $product2 = Produit::factory()->create(['categorie_id' => $category->id, 'price' => 15, 'quantity' => 30]);

        $orderPayload = [
            'products' => [
                ['product_id' => $product1->id, 'quantity' => 2],
                ['product_id' => $product2->id, 'quantity' => 3],
            ]
        ];

        // 2. Action
        $response = $this->actingAs($customer, 'sanctum')->postJson('/api/v1/customer/orders', $orderPayload);

        // 3. Assertions
        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'num',
                    'total_price',
                    'products' => [
                        '*' => ['id', 'name']
                    ]
                ]
            ])
            ->assertJsonFragment(['message' => 'Commande créée avec succès']);

        $this->assertDatabaseHas('commandes', [
            'customer_id' => $customer->id,
            'total_price' => (10 * 2) + (15 * 3), // 20 + 45 = 65
        ]);

        $this->assertDatabaseHas('commande_produit', [
            'produit_id' => $product1->id,
            'quantity' => 2,
        ]);

        $this->assertDatabaseHas('commande_produit', [
            'produit_id' => $product2->id,
            'quantity' => 3,
        ]);

        $this->assertEquals(18, $product1->fresh()->quantity); // 20 - 2
        $this->assertEquals(27, $product2->fresh()->quantity); // 30 - 3
    }
}