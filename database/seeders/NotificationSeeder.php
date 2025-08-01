<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            echo "No users found to link notifications to. Please run UserSeeder first.\n";
            return;
        }

        // Example: Link notifications to a random user
        $randomUser = $users->random();

        Notification::create([
            'id' => \Illuminate\Support\Str::uuid(), // Generate a UUID for the primary key
            'type' => 'new_order',
            'notifiable_type' => User::class,
            'notifiable_id' => $randomUser->id,
            'data' => json_encode(['order_id' => 1, 'message' => 'New order received!']),
            'read_at' => null,
        ]);

        Notification::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'product_update',
            'notifiable_type' => User::class,
            'notifiable_id' => $randomUser->id,
            'data' => json_encode(['product_id' => 5, 'message' => 'Product price updated.']),
            'read_at' => now(),
        ]);

        Notification::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'new_message',
            'notifiable_type' => User::class,
            'notifiable_id' => $randomUser->id,
            'data' => json_encode(['sender_id' => 3, 'message' => 'You have a new message.']),
            'read_at' => null,
        ]);
    }
}