<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserType;
use App\Models\Document;
use App\Repositories\ProfileProgressRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileProgressTest extends TestCase
{
    use RefreshDatabase;

    private $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ProfileProgressRepository();
        // Seed basic user types
        UserType::factory()->create(['title' => 'Client']);
        UserType::factory()->create(['title' => 'Producteur']);
    }

    public function test_client_progress_with_documents()
    {
        $clientType = UserType::where('title', 'Client')->first();
        $client = User::factory()->create(['user_type_id' => $clientType->id, 'address' => '123 Main St']); // Fill required fields

        // 1. Progress with no documents
        $progress = $this->repository->calculateAndUpdateProgress($client);
        $this->assertNotEquals($progress->total_steps, $progress->completed_steps);

        // 2. Add one document
        Document::factory()->create(['user_id' => $client->id]);
        $progress = $this->repository->calculateAndUpdateProgress($client->fresh());
        $this->assertEquals($progress->total_steps, $progress->completed_steps);
    }

    public function test_producer_progress_with_documents()
    {
        $producerType = UserType::where('title', 'Producteur')->first();
        $producer = User::factory()->create(['user_type_id' => $producerType->id, 'address' => '123 Main St', 'organization_id' => 1]); // Fill required fields

        // 1. Progress with no documents
        $progress = $this->repository->calculateAndUpdateProgress($producer);
        $this->assertNotEquals($progress->total_steps, $progress->completed_steps);

        // 2. Add one document
        Document::factory()->create(['user_id' => $producer->id]);
        $progress = $this->repository->calculateAndUpdateProgress($producer->fresh());
        $this->assertNotEquals($progress->total_steps, $progress->completed_steps);

        // 3. Add a second document
        Document::factory()->create(['user_id' => $producer->id]);
        $progress = $this->repository->calculateAndUpdateProgress($producer->fresh());
        $this->assertEquals($progress->total_steps, $progress->completed_steps);
    }

    /** @test */
    public function test_new_user_progress_with_missing_fields()
    {
        $clientType = UserType::where('title', 'Client')->first();
        // Create a client with a missing address
        $client = User::factory()->create(['user_type_id' => $clientType->id, 'address' => null]);

        $progress = $this->repository->calculateAndUpdateProgress($client);

        $this->assertLessThan(100, $progress->completion_percentage);
        $this->assertNotEquals($progress->total_steps, $progress->completed_steps);
    }

    /** @test */
    public function test_producer_partial_progress_with_one_document()
    {
        $producerType = UserType::where('title', 'Producteur')->first();
        $producer = User::factory()->create(['user_type_id' => $producerType->id, 'address' => '123 Main St', 'organization_id' => 1]);

        // Add one document
        Document::factory()->create(['user_id' => $producer->id]);
        $progress = $this->repository->calculateAndUpdateProgress($producer->fresh());

        $this->assertLessThan(100, $progress->completion_percentage);
        $this->assertNotEquals($progress->total_steps, $progress->completed_steps);
    }

    /** @test */
    public function test_progress_decreases_when_document_is_deleted()
    {
        $clientType = UserType::where('title', 'Client')->first();
        $client = User::factory()->create(['user_type_id' => $clientType->id, 'address' => '123 Main St']);

        // 1. Complete profile with one document
        $document = Document::factory()->create(['user_id' => $client->id]);
        $progressBefore = $this->repository->calculateAndUpdateProgress($client->fresh());
        $this->assertEquals(100, $progressBefore->completion_percentage);

        // 2. Delete the document
        $document->delete();
        $progressAfter = $this->repository->calculateAndUpdateProgress($client->fresh());

        // 3. Assert that progress has decreased
        $this->assertLessThan(100, $progressAfter->completion_percentage);
        $this->assertNotEquals($progressAfter->total_steps, $progressAfter->completed_steps);
    }
}
