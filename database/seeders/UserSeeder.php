<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@gmail.com',
                'password' => Hash::make('user123'),
                'phone' => '0812-3456-7890',
                'address' => 'Perumahan Taman Baloi, Blok C No. 15, Batam',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@gmail.com',
                'password' => Hash::make('user123'),
                'phone' => '0813-9876-5432',
                'address' => 'Komplek Harmoni Residence, Nagoya, Batam',
            ],
            [
                'name' => 'Andi Wijaya',
                'email' => 'andi@gmail.com',
                'password' => Hash::make('user123'),
                'phone' => '0821-5555-1234',
                'address' => 'Villa Bukit Indah, Sekupang, Batam',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
