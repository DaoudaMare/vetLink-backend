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
                $participants = $conversation->users->pluck('id')->toArray();
            if (empty($participants)) {
                continue; // Skip if no participants found for some reason
            }
            $senderId = $faker->randomElement($participants);

                $messageData = [
                    'conversation_id' => $conversation->id,
                    'sender_id' => $senderId,
                    'message' => $faker->sentence(),
                    'is_read' => $faker->boolean(),
                ];

                // 1 in 10 chance to create an image message
                if (rand(1, 10) === 1) {
                    $messageData['attachment_type'] = 'image';
                    $messageData['attachment_path'] = 'https://picsum.photos/seed/' . rand() . '/400/300';
                    // 50% chance to have a caption
                    if(rand(0, 1) === 0) {
                         $messageData['message'] = $faker->sentence(5);
                    } else {
                        $messageData['message'] = 'image_' . time() . '.jpg';
                    }
                } else {
                    $messageData['attachment_type'] = null;
                    $messageData['attachment_path'] = null;
                }

                Message::create($messageData);
            }
        }
    }
}
