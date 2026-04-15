<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // --- Admin ---
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@eventify.com',
            'password' => Hash::make('password'),
            'role'     => '1',   // 1 = admin
            'phone'    => '081200000001',
            'status'   => true,
        ]);

        // --- Organizers ---
        $organizers = [
            [
                'name'  => 'Budi Santoso',
                'email' => 'budi@eventify.com',
                'phone' => '081200000002',
            ],
            [
                'name'  => 'Sari Dewi',
                'email' => 'sari@eventify.com',
                'phone' => '081200000003',
            ],
        ];

        foreach ($organizers as $organizer) {
            User::create([
                'name'     => $organizer['name'],
                'email'    => $organizer['email'],
                'password' => Hash::make('password'),
                'role'     => '2',   // 2 = organizer (after approval)
                'phone'    => $organizer['phone'],
                'status'   => true,
            ]);
        }

        // --- Regular Users ---
        $users = [
            ['name' => 'Andi Wijaya',    'email' => 'andi@mail.com',   'phone' => '082100000001'],
            ['name' => 'Rina Kusuma',    'email' => 'rina@mail.com',   'phone' => '082100000002'],
            ['name' => 'Doni Pratama',   'email' => 'doni@mail.com',   'phone' => '082100000003'],
            ['name' => 'Maya Lestari',   'email' => 'maya@mail.com',   'phone' => '082100000004'],
            ['name' => 'Arif Rahman',    'email' => 'arif@mail.com',   'phone' => '082100000005'],
        ];

        foreach ($users as $user) {
            User::create([
                'name'     => $user['name'],
                'email'    => $user['email'],
                'password' => Hash::make('password'),
                'role'     => '3',   // 3 = regular user
                'phone'    => $user['phone'],
                'status'   => true,
            ]);
        }
    }
}
