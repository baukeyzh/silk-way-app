<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!DB::table('translations')->where('key', 'cargo.edit_desc')->exists()) {
            DB::table('translations')->insert([
                'key'   => 'cargo.edit_desc',
                'group' => 'cargo',
                'ru'    => 'Измените информацию о грузе',
                'kz'    => 'Жүк туралы ақпаратты өзгертіңіз',
                'cn'    => '修改货物信息',
            ]);
        }
    }

    public function down(): void
    {
        DB::table('translations')->where('key', 'cargo.edit_desc')->delete();
    }
};
