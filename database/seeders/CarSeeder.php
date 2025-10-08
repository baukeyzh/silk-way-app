<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Car;
use App\Models\User;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Получаем всех водителей
        $drivers = User::where('role', 'driver')->get();

        if ($drivers->count() === 0) {
            $this->command->info('Нет водителей для создания машин. Сначала создайте пользователей с ролью driver.');
            return;
        }

        $trailerTypes = Car::getTrailerTypes();
        $trailerTypeKeys = array_keys($trailerTypes);

        foreach ($drivers as $driver) {
            // Создаем 1-3 машины для каждого водителя
            $carCount = rand(1, 3);
            
            for ($i = 0; $i < $carCount; $i++) {
                $trailerType = $trailerTypeKeys[array_rand($trailerTypeKeys)];
                
                $trailerLength = rand(8, 20) + (rand(0, 9) / 10); // 8.0 - 20.9 метра
                $trailerWidth = rand(2, 3) + (rand(0, 9) / 10);   // 2.0 - 3.9 метра
                $trailerHeight = rand(2, 4) + (rand(0, 9) / 10);  // 2.0 - 4.9 метра
                
                Car::create([
                    'user_id' => $driver->id,
                    'brand' => $this->getRandomBrand(),
                    'model' => $this->getRandomModel(),
                    'license_plate' => $this->generateLicensePlate(),
                    'max_weight' => rand(10, 50) + (rand(0, 9) / 10), // 10.0 - 50.9 тонн
                    'trailer_length' => $trailerLength,
                    'trailer_width' => $trailerWidth,
                    'trailer_height' => $trailerHeight,
                    'trailer_volume' => round($trailerLength * $trailerWidth * $trailerHeight, 2),
                    'trailer_type' => $trailerType,
                    'trailer_type_rus' => $trailerTypes[$trailerType], // Исправлено: было trailer_type_ru
                    'is_active' => rand(0, 10) > 1, // 90% машин активны
                ]);
            }
        }

        $this->command->info('Машины успешно созданы!');
    }

    private function getRandomBrand(): string
    {
        $brands = [
            'Mercedes-Benz', 'Volvo', 'Scania', 'MAN', 'Iveco', 'DAF', 'Renault', 'Ford',
            'ГАЗ', 'ЗИЛ', 'КамАЗ', 'Урал', 'МАЗ', 'КрАЗ'
        ];
        
        return $brands[array_rand($brands)];
    }

    private function getRandomModel(): string
    {
        $models = [
            'Sprinter', 'Vito', 'Actros', 'Atego', 'FH', 'FM', 'P', 'R', 'G', 'T',
            'Next', 'Daily', 'Eurocargo', 'Stralis', 'XF', 'CF', 'LF', 'XF105',
            '3307', '3309', '43253', '5320', '4310', '4320', '53212', '53215'
        ];
        
        return $models[array_rand($models)];
    }

    private function generateLicensePlate(): string
    {
        $letters = ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я'];
        
        $letter1 = $letters[array_rand($letters)];
        $letter2 = $letters[array_rand($letters)];
        $letter3 = $letters[array_rand($letters)];
        
        $numbers = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $region = str_pad(rand(1, 999), 2, '0', STR_PAD_LEFT);
        
        return $letter1 . $numbers . $letter2 . $letter3 . $region;
    }
}
