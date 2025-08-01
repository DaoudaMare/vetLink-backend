<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserType;
use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed necessary data
        $this->artisan('db:seed', ['--class' => 'UserTypeSeeder']);
    }

    /** @test */
    public function a_user_can_start_a_conversation_with_another_user()
    {
        // 1. Setup
        $clientType = UserType::where('title', 'Client')->first();
        $producerType = UserType::where('title', 'Producteur')->first();

        $client = User::factory()->create(['user_type_id' => $clientType->id]);
        $producer = User::factory()->create(['user_type_id' => $producerType->id]);

        // 2. Action
        $response = $this->actingAs($client, 'sanctum')->postJson('/api/chat/conversations/start', [
            'user_id' => $producer->id,
        ]);

        // 3. Assertions
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'created_at',
                'updated_at',
                'users' => [
                    '*' => ['id', 'firstName', 'lastName']
                ]
            ]);

        $this->assertDatabaseHas('conversations', [
            // No specific columns to check here other than existence
        ]);

        // Get the conversation created from the response
        $conversationId = $response->json('id');

        $this->assertDatabaseHas('conversation_user', [
            'conversation_id' => $conversationId,
            'user_id' => $client->id,
        ]);

        $this->assertDatabaseHas('conversation_user', [
            'conversation_id' => $conversationId,
            'user_id' => $producer->id,
        ]);
    }

    /** @test */
    public function a_user_can_send_a_message_in_a_conversation()
    {
        // 1. Setup: Create two users and a conversation between them
        $clientType = UserType::where('title', 'Client')->first();
        $producerType = UserType::where('title', 'Producteur')->first();

        $client = User::factory()->create(['user_type_id' => $clientType->id]);
        $producer = User::factory()->create(['user_type_id' => $producerType->id]);

        Event::fake();

        $conversation = Conversation::create();
        $conversation->users()->attach([$client->id, $producer->id]);

        $messageBody = 'Hello, this is a test message.';

        // 2. Action: Client sends a message to the producer
        $response = $this->actingAs($client, 'sanctum')->postJson("/api/chat/conversations/{$conversation->id}/messages", [
            'body' => $messageBody,
        ]);

        // 3. Assertions
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'body',
                'user_id',
                'conversation_id',
                'created_at',
                'user' => ['id', 'firstName']
            ])
            ->assertJsonFragment(['body' => $messageBody]);

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'user_id' => $client->id,
            'body' => $messageBody,
        ]);
    }
}
