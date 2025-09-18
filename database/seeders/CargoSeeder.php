<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cargo;
use Illuminate\Support\Facades\Hash;

class CargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем администратора
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Администратор',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'approved' => true,
            ]
        );

        // Создаем тестовых пользователей
        $warehouseEmployee = User::firstOrCreate(
            ['email' => 'warehouse@example.com'],
            [
                'name' => 'Иван Петров',
                'password' => Hash::make('password'),
                'role' => 'warehouse_employee',
                'approved' => true,
            ]
        );

        $driver = User::firstOrCreate(
            ['email' => 'driver@example.com'],
            [
                'name' => 'Алексей Сидоров',
                'password' => Hash::make('password'),
                'role' => 'driver',
                'approved' => true,
            ]
        );

        // Создаем тестовые грузы только если их еще нет
        if (Cargo::count() === 0) {
            Cargo::create([
                'from_location' => 'Алматы',
                'to_location' => 'Астана',
                'cargo_type' => 'Электроника',
                'volume' => 2.5,
                'weight' => 150.0,
                'ready_date' => now()->addHours(2),
                'comment' => 'Хрупкий груз, требует осторожной перевозки',
                'status' => 'available',
                'created_by' => $warehouseEmployee->id,
            ]);

            Cargo::create([
                'from_location' => 'Шымкент',
                'to_location' => 'Алматы',
                'cargo_type' => 'Одежда',
                'volume' => 5.0,
                'weight' => 300.0,
                'ready_date' => now()->addHours(4),
                'comment' => 'Контакт: +7 777 123 45 67',
                'status' => 'available',
                'created_by' => $warehouseEmployee->id,
            ]);

            Cargo::create([
                'from_location' => 'Астана',
                'to_location' => 'Караганда',
                'cargo_type' => 'Мебель',
                'volume' => 8.0,
                'weight' => 500.0,
                'ready_date' => now()->addHours(1),
                'comment' => 'Требуется грузовик с бортом',
                'status' => 'available',
                'created_by' => $warehouseEmployee->id,
            ]);
        }
    }
}
