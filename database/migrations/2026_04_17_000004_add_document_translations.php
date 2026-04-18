<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $rows = [
            ['key' => 'docs.title',              'group' => 'docs', 'ru' => 'Мои документы',                'kz' => 'Менің құжаттарым',                    'cn' => '我的文件'],
            ['key' => 'docs.description',        'group' => 'docs', 'ru' => 'Загрузите документы для верификации', 'kz' => 'Тексеру үшін құжаттарды жүктеңіз', 'cn' => '上传文件以进行验证'],
            ['key' => 'docs.driver_license',     'group' => 'docs', 'ru' => 'Права',                        'kz' => 'Жүргізуші куәлігі',                   'cn' => '驾驶证'],
            ['key' => 'docs.vehicle_passport',   'group' => 'docs', 'ru' => 'Техпаспорт',                   'kz' => 'Техпаспорт',                          'cn' => '车辆登记证'],
            ['key' => 'docs.trailer_passport',   'group' => 'docs', 'ru' => 'Техпаспорт прицепа',           'kz' => 'Прицеп техпаспорты',                  'cn' => '拖车登记证'],
            ['key' => 'docs.category_cert',      'group' => 'docs', 'ru' => 'С кат',                        'kz' => 'С кат',                               'cn' => '类别证书'],
            ['key' => 'docs.green_card',         'group' => 'docs', 'ru' => 'Зеленая карта',                'kz' => 'Жасыл карта',                         'cn' => '绿卡'],
            ['key' => 'docs.insurance',          'group' => 'docs', 'ru' => 'Страховка',                    'kz' => 'Сақтандыру',                          'cn' => '保险'],
            ['key' => 'docs.status_not_uploaded','group' => 'docs', 'ru' => 'Не загружен',                  'kz' => 'Жүктелмеген',                         'cn' => '未上传'],
            ['key' => 'docs.status_pending',     'group' => 'docs', 'ru' => 'На проверке',                  'kz' => 'Тексерілуде',                         'cn' => '审核中'],
            ['key' => 'docs.status_verified',    'group' => 'docs', 'ru' => 'Проверен',                     'kz' => 'Тексерілді',                          'cn' => '已验证'],
            ['key' => 'docs.status_rejected',    'group' => 'docs', 'ru' => 'Отклонен',                     'kz' => 'Қабылданбады',                        'cn' => '已拒绝'],
            ['key' => 'docs.optional',           'group' => 'docs', 'ru' => 'Необязательный',               'kz' => 'Міндетті емес',                       'cn' => '可选'],
            ['key' => 'docs.upload_button',      'group' => 'docs', 'ru' => 'Загрузить',                    'kz' => 'Жүктеу',                              'cn' => '上传'],
            ['key' => 'docs.replace_button',     'group' => 'docs', 'ru' => 'Заменить',                     'kz' => 'Ауыстыру',                            'cn' => '替换'],
            ['key' => 'docs.delete_button',      'group' => 'docs', 'ru' => 'Удалить',                      'kz' => 'Жою',                                 'cn' => '删除'],
            ['key' => 'docs.expiry_date',        'group' => 'docs', 'ru' => 'Срок действия',                'kz' => 'Жарамдылық мерзімі',                  'cn' => '有效期'],
            ['key' => 'docs.rejection_reason',   'group' => 'docs', 'ru' => 'Причина отказа',               'kz' => 'Бас тарту себебі',                    'cn' => '拒绝原因'],
            ['key' => 'docs.verified_count',     'group' => 'docs', 'ru' => 'Проверено :count из :total',   'kz' => 'Тексерілді :count / :total',          'cn' => '已验证 :count / :total'],
            ['key' => 'docs.approve',            'group' => 'docs', 'ru' => 'Одобрить',                     'kz' => 'Мақұлдау',                            'cn' => '批准'],
            ['key' => 'docs.reject',             'group' => 'docs', 'ru' => 'Отклонить',                    'kz' => 'Бас тарту',                           'cn' => '拒绝'],
            ['key' => 'docs.admin_title',        'group' => 'docs', 'ru' => 'Документы водителей',          'kz' => 'Жүргізушілер құжаттары',              'cn' => '司机文件'],
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
            'docs.title', 'docs.description', 'docs.driver_license', 'docs.vehicle_passport',
            'docs.trailer_passport', 'docs.category_cert', 'docs.green_card', 'docs.insurance',
            'docs.status_not_uploaded', 'docs.status_pending', 'docs.status_verified',
            'docs.status_rejected', 'docs.optional', 'docs.upload_button', 'docs.replace_button',
            'docs.delete_button', 'docs.expiry_date', 'docs.rejection_reason',
            'docs.verified_count', 'docs.approve', 'docs.reject', 'docs.admin_title',
        ])->delete();
    }
};
