<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Hash;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed necessary user types for tests
        $this->artisan('db:seed', ['--class' => 'UserTypeSeeder']);
    }

    /** @test */
    public function test_user_can_register_with_valid_credentials()
    {
        $response = $this->postJson('/register', [
            'firstName' => 'Test',
            'lastName' => 'User',
            'email' => 'test@example.com',
            'tel1' => '1234567890',
            'password' => 'password',
            'password_confirmation' => 'password',
            'user_type_id' => UserType::where('title', 'Client')->first()->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'access_token',
                'token_type',
                'user' => ['id', 'firstName', 'email']
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'firstName' => 'Test',
        ]);
    }

    /** @test */
    public function test_user_cannot_register_with_duplicate_email()
    {
        User::factory()->create(['email' => 'duplicate@example.com']);

        $response = $this->postJson('/register', [
            'firstName' => 'Another',
            'lastName' => 'User',
            'email' => 'duplicate@example.com',
            'tel1' => '0987654321',
            'password' => 'password',
            'password_confirmation' => 'password',
            'user_type_id' => UserType::where('title', 'Client')->first()->id,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => Hash::make('password'),
            'user_type_id' => UserType::where('title', 'Client')->first()->id,
        ]);

        $response = $this->postJson('/login', [
            'email' => 'login@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'access_token',
                'token_type',
                'user' => ['id', 'firstName', 'email']
            ]);
    }

    /** @test */
    public function test_user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'invalid@example.com',
            'password' => Hash::make('password'),
            'user_type_id' => UserType::where('title', 'Client')->first()->id,
        ]);

        $response = $this->postJson('/login', [
            'email' => 'invalid@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Email ou mot de passe incorrect.']);
    }

    /** @test */
    public function test_authenticated_user_can_logout()
    {
        $user = User::factory()->create([
            'user_type_id' => UserType::where('title', 'Client')->first()->id,
        ]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/v1/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Déconnexion réussie.']);

        // Verify token is revoked
        $this->assertFalse($user->tokens()->where('token', hash('sha256', $token))->exists());

        // Try to access a protected route with the revoked token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/v1/users');

        $response->assertStatus(401);
    }

    /** @test */
    public function test_unauthenticated_user_cannot_access_protected_routes()
    {
        $response = $this->getJson('/v1/users');
        $response->assertStatus(401);
    }

    /** @test */
    public function test_client_cannot_access_producer_routes()
    {
        $clientType = UserType::where('title', 'Client')->first();
        $client = User::factory()->create(['user_type_id' => $clientType->id]);

        $response = $this->actingAs($client, 'sanctum')->postJson('/v1/producer/products', [
            'name' => 'Test Product',
            'description' => 'Description',
            'categorie_id' => 1,
            'quantity' => 10,
            'price' => 100,
            'measure' => 'kg',
            'isbio' => true,
        ]);

        $response->assertStatus(403);
    }
}
