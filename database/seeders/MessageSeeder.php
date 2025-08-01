<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $conversations = Conversation::with('users')->get();
        $faker = Faker::create();

        if ($conversations->isEmpty()) {
            $this->command->info('No conversations to seed messages into.');
            return;
        }

        foreach ($conversations as $conversation) {
            $numberOfMessages = rand(5, 15);

            for ($i = 0; $i < $numberOfMessages; $i++) {
                $messageData = [
                    'conversation_id' => $conversation->id,
                    'user_id' => $conversation->users->random()->id,
                ];

                // 1 in 10 chance to create an image message
                if (rand(1, 10) === 1) {
                    $messageData['message_type'] = 'image';
                    $messageData['file_url'] = 'https://picsum.photos/seed/' . rand() . '/400/300';
                    $messageData['file_name'] = 'image_' . time() . '.jpg';
                    // 50% chance to have a caption
                    $messageData['body'] = (rand(0, 1) === 0) ? $faker->sentence(5) : null;
                } else {
                    $messageData['message_type'] = 'text';
                    $messageData['body'] = $faker->sentence();
                }

                Message::create($messageData);
            }
        }
    }
}
