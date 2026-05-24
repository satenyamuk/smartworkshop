<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =============================================
        // ADMIN
        // =============================================
        User::updateOrCreate(
            ['email' => 'admin@smartworkshop.com'],
            [
                'name'                   => 'Admin SmartWorkshop',
                'password'               => Hash::make('admin123456'),
                'role'                   => 'admin',
                'is_approved_instructor' => false,
            ]
        );

        // =============================================
        // PANITIA / INSTRUCTOR  (sudah di-approve)
        // =============================================
        User::updateOrCreate(
            ['email' => 'panitia@smartworkshop.com'],
            [
                'name'                   => 'Panitia SmartWorkshop',
                'password'               => Hash::make('panitia123456'),
                'role'                   => 'instructor',
                'is_approved_instructor' => true,
            ]
        );

        // =============================================
        // SISWA DEMO
        // =============================================
        User::updateOrCreate(
            ['email' => 'siswa@smartworkshop.com'],
            [
                'name'                   => 'Siswa Demo',
                'password'               => Hash::make('siswa123456'),
                'role'                   => 'student',
                'is_approved_instructor' => false,
            ]
        );

        // =============================================
        // GURU DEMO
        // =============================================
        User::updateOrCreate(
            ['email' => 'guru@smartworkshop.com'],
            [
                'name'                   => 'Guru Demo',
                'password'               => Hash::make('guru123456'),
                'role'                   => 'teacher',
                'is_approved_instructor' => false,
            ]
        );
    }
}
