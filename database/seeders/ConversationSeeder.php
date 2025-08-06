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
            $userOne = $users->random();
            $userTwo = $users->where('id', '!=', $userOne->id)->random();

            Conversation::create([
                'user_one_id' => $userOne->id,
                'user_two_id' => $userTwo->id,
            ]);
        }
    }
}