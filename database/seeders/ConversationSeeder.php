<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Database\Seeder;

class ConversationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->count() < 2) {
            $this->command->info('Not enough users to create conversations.');
            return;
        }

        for ($i = 0; $i < 10; $i++) {
            $conversationUsers = $users->random(2);

            $conversation = Conversation::create();
            $conversation->users()->attach($conversationUsers);
        }
    }
}