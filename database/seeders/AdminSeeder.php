<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus admin lama jika ada
        Admin::where('email', 'admin@sonsun.com')->delete();
        
        // Buat admin baru
        Admin::create([
            'name' => 'Admin Sonsun',
            'email' => 'admin@sonsun.com',
            'password' => Hash::make('12345'),
        ]);
    }
}
