<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $rows = [
            ['key' => 'cargo.price_usd',   'group' => 'cargo',   'ru' => 'Цена (USD)',          'kz' => 'Бағасы (USD)',        'cn' => '价格（美元）'],
            ['key' => 'cargo.select_city', 'group' => 'cargo',   'ru' => '— Выберите город —',  'kz' => '— Қала таңдаңыз —',  'cn' => '— 请选择城市 —'],
            ['key' => 'country.kz',        'group' => 'country', 'ru' => 'Казахстан',            'kz' => 'Қазақстан',           'cn' => '哈萨克斯坦'],
            ['key' => 'country.ru',        'group' => 'country', 'ru' => 'Россия',               'kz' => 'Ресей',               'cn' => '俄罗斯'],
            ['key' => 'country.cn',        'group' => 'country', 'ru' => 'Китай',                'kz' => 'Қытай',               'cn' => '中国'],
        ];

        foreach ($rows as $row) {
            if (!DB::table('translations')->where('key', $row['key'])->exists()) {
                DB::table('translations')->insert($row);
            }
        }
    }

    public function down(): void
    {
        DB::table('translations')->whereIn('key', [
            'cargo.price_usd',
            'cargo.select_city',
            'country.kz',
            'country.ru',
            'country.cn',
        ])->delete();
    }
};
