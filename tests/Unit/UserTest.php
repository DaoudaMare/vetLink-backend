<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_correctly_identifies_a_producer()
    {
        $producerType = UserType::factory()->create(['title' => 'Producteur']);
        $customerType = UserType::factory()->create(['title' => 'Client']);

        $producer = User::factory()->create(['user_type_id' => $producerType->id]);
        $customer = User::factory()->create(['user_type_id' => $customerType->id]);

        $this->assertTrue($producer->isProducer());
        $this->assertFalse($customer->isProducer());
    }

    /** @test */
    public function it_correctly_identifies_a_customer()
    {
        $producerType = UserType::factory()->create(['title' => 'Producteur']);
        $customerType = UserType::factory()->create(['title' => 'Client']);

        $producer = User::factory()->create(['user_type_id' => $producerType->id]);
        $customer = User::factory()->create(['user_type_id' => $customerType->id]);

        $this->assertTrue($customer->isCustomer());
        $this->assertFalse($producer->isCustomer());
    }
}