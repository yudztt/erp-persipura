<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class CreateAdminUser extends Command
{
    protected $signature = 'user:create-admin';
    protected $description = 'Create admin user for Persipura ERP';

    public function handle()
    {
        $this->info('Creating admin user...');

        // Create role if not exists
        $role = Role::firstOrCreate(
            ['nama_role' => 'System Admin'],
            ['deskripsi' => 'Administrator dengan akses penuh']
        );

        // Create or update admin user
        $user = User::updateOrCreate(
            ['email' => 'admin@persipura.id'],
            [
                'id_role' => $role->id_role,
                'nama' => 'Admin Persipura',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        $this->info('✓ Admin user created successfully!');
        $this->info('');
        $this->info('Login credentials:');
        $this->info('Email: admin@persipura.id');
        $this->info('Password: admin123');
        $this->info('');
        
        return 0;
    }
}
