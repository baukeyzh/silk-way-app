<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $rows = [
            ['key' => 'cargo.show_title',              'group' => 'cargo',        'ru' => 'Просмотр груза - Silk Way',                                               'kz' => 'Жүкті қарау - Silk Way',                                    'cn' => '查看货物 - Silk Way'],
            ['key' => 'cargo.show_heading',            'group' => 'cargo',        'ru' => 'Информация о грузе',                                                      'kz' => 'Жүк туралы ақпарат',                                        'cn' => '货物信息'],
            ['key' => 'cargo.show_desc',               'group' => 'cargo',        'ru' => 'Детальная информация о грузе и его статусе',                              'kz' => 'Жүк және оның күйі туралы толық ақпарат',                   'cn' => '货物及其状态的详细信息'],
            ['key' => 'cargo.volume_weight_label',     'group' => 'cargo',        'ru' => 'Объем и вес',                                                             'kz' => 'Көлем және салмақ',                                         'cn' => '体积和重量'],
            ['key' => 'cargo.ready_date_label',        'group' => 'cargo',        'ru' => 'Дата готовности',                                                         'kz' => 'Дайындық күні',                                             'cn' => '准备日期'],
            ['key' => 'cargo.comment_label',           'group' => 'cargo',        'ru' => 'Комментарий',                                                             'kz' => 'Түсініктеме',                                               'cn' => '评论'],
            ['key' => 'cargo.driver_label',            'group' => 'cargo',        'ru' => 'Водитель',                                                                'kz' => 'Жүргізуші',                                                 'cn' => '司机'],
            ['key' => 'cargo.created_by_label',        'group' => 'cargo',        'ru' => 'Создан',                                                                  'kz' => 'Құрылған',                                                  'cn' => '创建者'],
            ['key' => 'cargo.applications_heading',    'group' => 'cargo',        'ru' => 'Заявки на груз',                                                          'kz' => 'Жүкке өтініштер',                                           'cn' => '货物申请'],
            ['key' => 'cargo.applications_desc',       'group' => 'cargo',        'ru' => 'Заявки от водителей на перевозку этого груза',                            'kz' => 'Жүргізушілердің бұл жүкті тасымалдауға өтініштері',         'cn' => '司机对该货物运输的申请'],
            ['key' => 'cargo.my_application_heading',  'group' => 'cargo',        'ru' => 'Ваша заявка',                                                             'kz' => 'Сіздің өтінішіңіз',                                         'cn' => '您的申请'],
            ['key' => 'cargo.my_application_desc',     'group' => 'cargo',        'ru' => 'Статус вашей заявки на этот груз',                                        'kz' => 'Бұл жүкке өтінішіңіздің күйі',                             'cn' => '您对该货物申请的状态'],
            ['key' => 'cargo.apply_button',            'group' => 'cargo',        'ru' => 'Подать заявку',                                                           'kz' => 'Өтініш беру',                                               'cn' => '提交申请'],
            ['key' => 'applications.table_notes',      'group' => 'applications', 'ru' => 'Заметки',                                                                 'kz' => 'Ескертпелер',                                               'cn' => '备注'],
            ['key' => 'applications.no_notes',         'group' => 'applications', 'ru' => 'Нет заметок',                                                             'kz' => 'Ескертпелер жоқ',                                           'cn' => '无备注'],
            ['key' => 'applications.apply_modal_title','group' => 'applications', 'ru' => 'Подать заявку на груз',                                                   'kz' => 'Жүкке өтініш беру',                                         'cn' => '申请货物运输'],
            ['key' => 'applications.my_notes_optional','group' => 'applications', 'ru' => 'Ваши заметки (необязательно)',                                            'kz' => 'Сіздің ескертпелеріңіз (міндетті емес)',                     'cn' => '您的备注（可选）'],
            ['key' => 'applications.apply_placeholder','group' => 'applications', 'ru' => 'Дополнительная информация о вашем опыте или условиях перевозки...',       'kz' => 'Тәжірибеңіз немесе тасымалдау шарттары туралы қосымша ақпарат...', 'cn' => '关于您的经验或运输条件的附加信息...'],
            ['key' => 'applications.my_notes_label',   'group' => 'applications', 'ru' => 'Ваши заметки:',                                                           'kz' => 'Сіздің ескертпелеріңіз:',                                   'cn' => '您的备注：'],
            ['key' => 'cargo.edit_desc',               'group' => 'cargo',        'ru' => 'Измените информацию о грузе',                                             'kz' => 'Жүк туралы ақпаратты өзгертіңіз',                          'cn' => '修改货物信息'],
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
            'cargo.show_title',
            'cargo.show_heading',
            'cargo.show_desc',
            'cargo.volume_weight_label',
            'cargo.ready_date_label',
            'cargo.comment_label',
            'cargo.driver_label',
            'cargo.created_by_label',
            'cargo.applications_heading',
            'cargo.applications_desc',
            'cargo.my_application_heading',
            'cargo.my_application_desc',
            'cargo.apply_button',
            'applications.table_notes',
            'applications.no_notes',
            'applications.apply_modal_title',
            'applications.my_notes_optional',
            'applications.apply_placeholder',
            'applications.my_notes_label',
            'cargo.edit_desc',
        ])->delete();
    }
};
