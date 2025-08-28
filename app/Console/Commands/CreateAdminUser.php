<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserType;
use App\Models\Organization;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user for VetLink';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating admin user...');

        // Créer le type d'utilisateur Admin s'il n'existe pas
        $adminType = UserType::firstOrCreate(
            ['title' => 'Admin'],
            ['title' => 'Admin']
        );

        // Créer l'organisation Admin si elle n'existe pas
        $adminOrg = Organization::firstOrCreate(
            ['name' => 'VetLink Admin'],
            [
                'name' => 'VetLink Admin',
                'adresse' => 'Siège social',
                'email' => 'admin@vetlink.com',
                'tel1' => '0000000000',
                'business_sector_id' => 1,
                'organization_type_id' => 1
            ]
        );

        // Vérifier si l'utilisateur admin existe déjà
        $existingAdmin = User::where('email', 'admin@vetlink.com')->first();
        
        if ($existingAdmin) {
            $this->warn('Admin user already exists!');
            $this->info('Email: admin@vetlink.com');
            
            // Réinitialiser le mot de passe
            $existingAdmin->update([
                'password' => Hash::make('password123')
            ]);
            $this->info('Password reset to: password123');
            return;
        }

        // Créer l'utilisateur admin
        $user = User::create([
            'firstName' => 'Admin',
            'lastName' => 'System',
            'email' => 'admin@vetlink.com',
            'tel1' => '0000000000',
            'password' => Hash::make('password123'),
            'user_type_id' => $adminType->id,
            'organisation_id' => $adminOrg->id,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $this->info('✅ Admin user created successfully!');
        $this->info('📧 Email: admin@vetlink.com');
        $this->info('🔑 Password: password123');
        $this->info('🌐 Login URL: http://127.0.0.1:8000/vetlink/login');
    }
}
