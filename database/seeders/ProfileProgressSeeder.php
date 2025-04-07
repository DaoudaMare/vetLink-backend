<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProfileProgressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::pluck('id')->toArray();
        $profileProgressData = [];

        foreach ($users as $userId) {
            $profileProgressData[] = [
                'id' => Str::uuid(),
                'user_id' => $userId,
                'completion_percentage' => 10, // 🔥 Fixé à 10
                'status' => 'incomplete', // Statut par défaut
                'last_reminder_at' => null, // Pas de rappel par défaut
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('profile_progress')->insert($profileProgressData);

    }
}
