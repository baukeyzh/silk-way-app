<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        City::truncate();

        $cities = [
            // Казахстан
            ['country' => 'kz', 'name' => 'Алматы',          'name_rus' => 'Алматы',          'name_kaz' => 'Алматы',           'name_chn' => '阿拉木图'],
            ['country' => 'kz', 'name' => 'Астана',           'name_rus' => 'Астана',           'name_kaz' => 'Астана',            'name_chn' => '阿斯塔纳'],
            ['country' => 'kz', 'name' => 'Шымкент',          'name_rus' => 'Шымкент',          'name_kaz' => 'Шымкент',           'name_chn' => '奇姆肯特'],
            ['country' => 'kz', 'name' => 'Қарағанды',        'name_rus' => 'Караганда',        'name_kaz' => 'Қарағанды',         'name_chn' => '卡拉干达'],
            ['country' => 'kz', 'name' => 'Ақтөбе',           'name_rus' => 'Актобе',           'name_kaz' => 'Ақтөбе',            'name_chn' => '阿克托别'],
            ['country' => 'kz', 'name' => 'Тараз',            'name_rus' => 'Тараз',            'name_kaz' => 'Тараз',             'name_chn' => '塔拉兹'],
            ['country' => 'kz', 'name' => 'Павлодар',         'name_rus' => 'Павлодар',         'name_kaz' => 'Павлодар',          'name_chn' => '巴甫洛达尔'],
            ['country' => 'kz', 'name' => 'Өскемен',          'name_rus' => 'Усть-Каменогорск', 'name_kaz' => 'Өскемен',           'name_chn' => '乌斯季卡缅诺戈尔斯克'],
            ['country' => 'kz', 'name' => 'Семей',            'name_rus' => 'Семей',            'name_kaz' => 'Семей',             'name_chn' => '塞梅伊'],
            ['country' => 'kz', 'name' => 'Атырау',           'name_rus' => 'Атырау',           'name_kaz' => 'Атырау',            'name_chn' => '阿特劳'],
            ['country' => 'kz', 'name' => 'Қостанай',         'name_rus' => 'Костанай',         'name_kaz' => 'Қостанай',          'name_chn' => '科斯塔奈'],
            ['country' => 'kz', 'name' => 'Қызылорда',        'name_rus' => 'Кызылорда',        'name_kaz' => 'Қызылорда',         'name_chn' => '克孜勒奥尔达'],
            ['country' => 'kz', 'name' => 'Орал',             'name_rus' => 'Уральск',          'name_kaz' => 'Орал',              'name_chn' => '乌拉尔斯克'],
            ['country' => 'kz', 'name' => 'Петропавл',        'name_rus' => 'Петропавловск',    'name_kaz' => 'Петропавл',         'name_chn' => '彼得罗巴甫洛夫斯克'],
            ['country' => 'kz', 'name' => 'Ақтау',            'name_rus' => 'Актау',            'name_kaz' => 'Ақтау',             'name_chn' => '阿克套'],
            ['country' => 'kz', 'name' => 'Түркістан',        'name_rus' => 'Туркестан',        'name_kaz' => 'Түркістан',         'name_chn' => '突厥斯坦'],
            ['country' => 'kz', 'name' => 'Көкшетау',         'name_rus' => 'Кокшетау',         'name_kaz' => 'Көкшетау',          'name_chn' => '科克舍套'],
            ['country' => 'kz', 'name' => 'Талдықорған',      'name_rus' => 'Талдыкорган',      'name_kaz' => 'Талдықорған',       'name_chn' => '塔尔迪库尔干'],
            ['country' => 'kz', 'name' => 'Экібастұз',        'name_rus' => 'Экибастуз',        'name_kaz' => 'Екібастұз',         'name_chn' => '埃基巴斯图兹'],
            ['country' => 'kz', 'name' => 'Рудный',           'name_rus' => 'Рудный',           'name_kaz' => 'Рудный',            'name_chn' => '鲁德内'],

            // Россия
            ['country' => 'ru', 'name' => 'Москва',           'name_rus' => 'Москва',           'name_kaz' => 'Мәскеу',            'name_chn' => '莫斯科'],
            ['country' => 'ru', 'name' => 'Санкт-Петербург',  'name_rus' => 'Санкт-Петербург',  'name_kaz' => 'Санкт-Петербург',   'name_chn' => '圣彼得堡'],
            ['country' => 'ru', 'name' => 'Новосибирск',      'name_rus' => 'Новосибирск',      'name_kaz' => 'Новосибирск',       'name_chn' => '新西伯利亚'],
            ['country' => 'ru', 'name' => 'Екатеринбург',     'name_rus' => 'Екатеринбург',     'name_kaz' => 'Екатеринбург',      'name_chn' => '叶卡捷琳堡'],
            ['country' => 'ru', 'name' => 'Казань',           'name_rus' => 'Казань',           'name_kaz' => 'Қазан',             'name_chn' => '喀山'],
            ['country' => 'ru', 'name' => 'Нижний Новгород',  'name_rus' => 'Нижний Новгород',  'name_kaz' => 'Нижний Новгород',   'name_chn' => '下诺夫哥罗德'],
            ['country' => 'ru', 'name' => 'Челябинск',        'name_rus' => 'Челябинск',        'name_kaz' => 'Челябинск',         'name_chn' => '车里雅宾斯克'],
            ['country' => 'ru', 'name' => 'Омск',             'name_rus' => 'Омск',             'name_kaz' => 'Омск',              'name_chn' => '鄂木斯克'],
            ['country' => 'ru', 'name' => 'Самара',           'name_rus' => 'Самара',           'name_kaz' => 'Самара',            'name_chn' => '萨马拉'],
            ['country' => 'ru', 'name' => 'Ростов-на-Дону',   'name_rus' => 'Ростов-на-Дону',   'name_kaz' => 'Ростов-на-Дону',    'name_chn' => '顿河畔罗斯托夫'],
            ['country' => 'ru', 'name' => 'Уфа',              'name_rus' => 'Уфа',              'name_kaz' => 'Уфа',               'name_chn' => '乌法'],
            ['country' => 'ru', 'name' => 'Красноярск',       'name_rus' => 'Красноярск',       'name_kaz' => 'Красноярск',        'name_chn' => '克拉斯诺亚尔斯克'],
            ['country' => 'ru', 'name' => 'Пермь',            'name_rus' => 'Пермь',            'name_kaz' => 'Пермь',             'name_chn' => '彼尔姆'],
            ['country' => 'ru', 'name' => 'Воронеж',          'name_rus' => 'Воронеж',          'name_kaz' => 'Воронеж',           'name_chn' => '沃罗涅日'],
            ['country' => 'ru', 'name' => 'Волгоград',        'name_rus' => 'Волгоград',        'name_kaz' => 'Волгоград',         'name_chn' => '伏尔加格勒'],
            ['country' => 'ru', 'name' => 'Краснодар',        'name_rus' => 'Краснодар',        'name_kaz' => 'Краснодар',         'name_chn' => '克拉斯诺达尔'],
            ['country' => 'ru', 'name' => 'Иркутск',          'name_rus' => 'Иркутск',          'name_kaz' => 'Иркутск',           'name_chn' => '伊尔库茨克'],
            ['country' => 'ru', 'name' => 'Хабаровск',        'name_rus' => 'Хабаровск',        'name_kaz' => 'Хабаровск',         'name_chn' => '哈巴罗夫斯克'],
            ['country' => 'ru', 'name' => 'Владивосток',      'name_rus' => 'Владивосток',      'name_kaz' => 'Владивосток',       'name_chn' => '符拉迪沃斯托克'],
            ['country' => 'ru', 'name' => 'Тюмень',           'name_rus' => 'Тюмень',           'name_kaz' => 'Тюмень',            'name_chn' => '秋明'],

            // Китай
            ['country' => 'cn', 'name' => 'Пекин',            'name_rus' => 'Пекин',            'name_kaz' => 'Бейжің',            'name_chn' => '北京'],
            ['country' => 'cn', 'name' => 'Шанхай',           'name_rus' => 'Шанхай',           'name_kaz' => 'Шанхай',            'name_chn' => '上海'],
            ['country' => 'cn', 'name' => 'Гуанчжоу',         'name_rus' => 'Гуанчжоу',         'name_kaz' => 'Гуанчжоу',          'name_chn' => '广州'],
            ['country' => 'cn', 'name' => 'Шэньчжэнь',        'name_rus' => 'Шэньчжэнь',        'name_kaz' => 'Шэньчжэнь',         'name_chn' => '深圳'],
            ['country' => 'cn', 'name' => 'Чэнду',            'name_rus' => 'Чэнду',            'name_kaz' => 'Чэнду',             'name_chn' => '成都'],
            ['country' => 'cn', 'name' => 'Чунцин',           'name_rus' => 'Чунцин',           'name_kaz' => 'Чунцин',            'name_chn' => '重庆'],
            ['country' => 'cn', 'name' => 'Ухань',            'name_rus' => 'Ухань',            'name_kaz' => 'Ухань',             'name_chn' => '武汉'],
            ['country' => 'cn', 'name' => 'Сиань',            'name_rus' => 'Сиань',            'name_kaz' => 'Сиань',             'name_chn' => '西安'],
            ['country' => 'cn', 'name' => 'Ханчжоу',          'name_rus' => 'Ханчжоу',          'name_kaz' => 'Ханчжоу',           'name_chn' => '杭州'],
            ['country' => 'cn', 'name' => 'Нанкин',           'name_rus' => 'Нанкин',           'name_kaz' => 'Нанкин',            'name_chn' => '南京'],
            ['country' => 'cn', 'name' => 'Тяньцзинь',        'name_rus' => 'Тяньцзинь',        'name_kaz' => 'Тяньцзинь',         'name_chn' => '天津'],
            ['country' => 'cn', 'name' => 'Харбин',           'name_rus' => 'Харбин',           'name_kaz' => 'Харбин',            'name_chn' => '哈尔滨'],
            ['country' => 'cn', 'name' => 'Урумчи',           'name_rus' => 'Урумчи',           'name_kaz' => 'Үрімжі',            'name_chn' => '乌鲁木齐'],
            ['country' => 'cn', 'name' => 'Ланьчжоу',         'name_rus' => 'Ланьчжоу',         'name_kaz' => 'Ланьчжоу',          'name_chn' => '兰州'],
            ['country' => 'cn', 'name' => 'Чжэнчжоу',        'name_rus' => 'Чжэнчжоу',         'name_kaz' => 'Чжэнчжоу',          'name_chn' => '郑州'],
            ['country' => 'cn', 'name' => 'Циндао',           'name_rus' => 'Циндао',           'name_kaz' => 'Циндао',            'name_chn' => '青岛'],
            ['country' => 'cn', 'name' => 'Далянь',           'name_rus' => 'Далянь',           'name_kaz' => 'Далянь',            'name_chn' => '大连'],
            ['country' => 'cn', 'name' => 'Куньмин',          'name_rus' => 'Куньмин',          'name_kaz' => 'Куньмин',           'name_chn' => '昆明'],
            ['country' => 'cn', 'name' => 'Сямэнь',           'name_rus' => 'Сямэнь',           'name_kaz' => 'Сямэнь',            'name_chn' => '厦门'],
            ['country' => 'cn', 'name' => 'Иу',               'name_rus' => 'Иу',               'name_kaz' => 'Иу',                'name_chn' => '义乌'],
        ];

        City::insert($cities);
    }
}
