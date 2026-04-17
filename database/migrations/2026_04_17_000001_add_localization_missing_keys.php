<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $rows = [
            ['key' => 'cargo.total',             'group' => 'cargo',  'ru' => 'Всего',                    'kz' => 'Барлығы',                      'cn' => '总计'],
            ['key' => 'cargo.status_in_transit',  'group' => 'cargo',  'ru' => 'В пути',                   'kz' => 'Жолда',                        'cn' => '运输中'],
            ['key' => 'cargo.my_application_col', 'group' => 'cargo',  'ru' => 'Моя заявка',               'kz' => 'Менің өтінімім',               'cn' => '我的申请'],
            ['key' => 'cargo.price_label',        'group' => 'cargo',  'ru' => 'Цена',                     'kz' => 'Бағасы',                       'cn' => '价格'],
            ['key' => 'cargo.ready_label',        'group' => 'cargo',  'ru' => 'Готов',                    'kz' => 'Дайын',                        'cn' => '准备就绪'],
            ['key' => 'cargo.app_pending',        'group' => 'cargo',  'ru' => 'На рассмотрении',          'kz' => 'Қарастырылуда',                'cn' => '待审批'],
            ['key' => 'cargo.app_pending_full',   'group' => 'cargo',  'ru' => 'Заявка на рассмотрении',   'kz' => 'Өтінім қарастырылуда',         'cn' => '申请待审批'],
            ['key' => 'cargo.app_approved',       'group' => 'cargo',  'ru' => 'Принята',                  'kz' => 'Қабылданды',                   'cn' => '已批准'],
            ['key' => 'cargo.app_approved_full',  'group' => 'cargo',  'ru' => 'Заявка принята',           'kz' => 'Өтінім қабылданды',            'cn' => '申请已批准'],
            ['key' => 'cargo.app_rejected',       'group' => 'cargo',  'ru' => 'Отклонена',                'kz' => 'Қабылданбады',                 'cn' => '已拒绝'],
            ['key' => 'cargo.app_rejected_full',  'group' => 'cargo',  'ru' => 'Заявка отклонена',         'kz' => 'Өтінім қабылданбады',          'cn' => '申请已拒绝'],
            ['key' => 'cargo.from_label',         'group' => 'cargo',  'ru' => 'Откуда',                   'kz' => 'Қайдан',                       'cn' => '从哪里'],
            ['key' => 'cargo.to_label',           'group' => 'cargo',  'ru' => 'Куда',                     'kz' => 'Қайда',                        'cn' => '到哪里'],
            ['key' => 'common.actions',           'group' => 'common', 'ru' => 'Действия',                 'kz' => 'Әрекеттер',                    'cn' => '操作'],
            ['key' => 'admin.translations',       'group' => 'admin',  'ru' => 'Переводы',                 'kz' => 'Аудармалар',                   'cn' => '翻译'],
            ['key' => 'admin.section_admin',      'group' => 'admin',  'ru' => 'Администрация',            'kz' => 'Әкімшілік',                    'cn' => '管理'],
            ['key' => 'admin.section_operations', 'group' => 'admin',  'ru' => 'Операции',                 'kz' => 'Операциялар',                  'cn' => '运营'],
            ['key' => 'admin.cities',             'group' => 'admin',  'ru' => 'Города',                   'kz' => 'Қалалар',                      'cn' => '城市'],
            ['key' => 'admin.collapse',           'group' => 'admin',  'ru' => 'Свернуть',                 'kz' => 'Жабу',                         'cn' => '收起'],
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
            'cargo.total', 'cargo.status_in_transit', 'cargo.my_application_col',
            'cargo.price_label', 'cargo.ready_label',
            'cargo.app_pending', 'cargo.app_pending_full',
            'cargo.app_approved', 'cargo.app_approved_full',
            'cargo.app_rejected', 'cargo.app_rejected_full',
            'cargo.from_label', 'cargo.to_label',
            'common.actions', 'admin.translations',
            'admin.section_admin', 'admin.section_operations',
            'admin.cities', 'admin.collapse',
        ])->delete();
    }
};
