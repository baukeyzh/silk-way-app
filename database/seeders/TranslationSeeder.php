<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $translations = [
            // Общие переводы
            [
                'key' => 'welcome',
                'rus' => 'Добро пожаловать',
                'kaz' => 'Қош келдіңіз',
                'chn' => '欢迎',
                'group' => 'general',
                'description' => 'Приветственное сообщение'
            ],
            [
                'key' => 'login',
                'rus' => 'Войти',
                'kaz' => 'Кіру',
                'chn' => '登录',
                'group' => 'auth',
                'description' => 'Кнопка входа'
            ],
            [
                'key' => 'logout',
                'rus' => 'Выйти',
                'kaz' => 'Шығу',
                'chn' => '退出',
                'group' => 'auth',
                'description' => 'Кнопка выхода'
            ],
            [
                'key' => 'email',
                'rus' => 'Email',
                'kaz' => 'Email',
                'chn' => '邮箱',
                'group' => 'auth',
                'description' => 'Поле email'
            ],
            [
                'key' => 'password',
                'rus' => 'Пароль',
                'kaz' => 'Құпия сөз',
                'chn' => '密码',
                'group' => 'auth',
                'description' => 'Поле пароля'
            ],
            [
                'key' => 'save',
                'rus' => 'Сохранить',
                'kaz' => 'Сақтау',
                'chn' => '保存',
                'group' => 'general',
                'description' => 'Кнопка сохранения'
            ],
            [
                'key' => 'edit',
                'rus' => 'Редактировать',
                'kaz' => 'Өңдеу',
                'chn' => '编辑',
                'group' => 'general',
                'description' => 'Кнопка редактирования'
            ],
            [
                'key' => 'delete',
                'rus' => 'Удалить',
                'kaz' => 'Жою',
                'chn' => '删除',
                'group' => 'general',
                'description' => 'Кнопка удаления'
            ],
            [
                'key' => 'cancel',
                'rus' => 'Отмена',
                'kaz' => 'Бас тарту',
                'chn' => '取消',
                'group' => 'general',
                'description' => 'Кнопка отмены'
            ],
            [
                'key' => 'back',
                'rus' => 'Назад',
                'kaz' => 'Артқа',
                'chn' => '返回',
                'group' => 'general',
                'description' => 'Кнопка назад'
            ],
            [
                'key' => 'next',
                'rus' => 'Далее',
                'kaz' => 'Келесі',
                'chn' => '下一步',
                'group' => 'general',
                'description' => 'Кнопка далее'
            ],
            [
                'key' => 'previous',
                'rus' => 'Предыдущий',
                'kaz' => 'Алдыңғы',
                'chn' => '上一个',
                'group' => 'general',
                'description' => 'Кнопка предыдущий'
            ],
            [
                'key' => 'search',
                'rus' => 'Поиск',
                'kaz' => 'Іздеу',
                'chn' => '搜索',
                'group' => 'general',
                'description' => 'Поле поиска'
            ],
            [
                'key' => 'search_placeholder',
                'rus' => 'Поиск по марке, модели или номеру...',
                'kaz' => 'Марка, модель немесе нөмір бойынша іздеу...',
                'chn' => '按品牌、型号或车牌号搜索...',
                'group' => 'general',
                'description' => 'Плейсхолдер поиска машин'
            ],
            [
                'key' => 'filter',
                'rus' => 'Фильтр',
                'kaz' => 'Сүзгі',
                'chn' => '筛选',
                'group' => 'general',
                'description' => 'Кнопка фильтра'
            ],
            [
                'key' => 'clear',
                'rus' => 'Очистить',
                'kaz' => 'Тазалау',
                'chn' => '清除',
                'group' => 'general',
                'description' => 'Кнопка очистки'
            ],
            
            // Переводы для грузов
            [
                'key' => 'cargo',
                'rus' => 'Груз',
                'kaz' => 'Жүк',
                'chn' => '货物',
                'group' => 'cargo',
                'description' => 'Название груза'
            ],
            [
                'key' => 'cargo_type',
                'rus' => 'Тип груза',
                'kaz' => 'Жүк түрі',
                'chn' => '货物类型',
                'group' => 'cargo',
                'description' => 'Поле типа груза'
            ],
            [
                'key' => 'from_location',
                'rus' => 'Откуда',
                'kaz' => 'Қайдан',
                'chn' => '从哪里',
                'group' => 'cargo',
                'description' => 'Поле места отправления'
            ],
            [
                'key' => 'to_location',
                'rus' => 'Куда',
                'kaz' => 'Қайда',
                'chn' => '到哪里',
                'group' => 'cargo',
                'description' => 'Поле места назначения'
            ],
            [
                'key' => 'volume',
                'rus' => 'Объем',
                'kaz' => 'Көлем',
                'chn' => '体积',
                'group' => 'cargo',
                'description' => 'Поле объема груза'
            ],
            [
                'key' => 'weight',
                'rus' => 'Вес',
                'kaz' => 'Салмақ',
                'chn' => '重量',
                'group' => 'cargo',
                'description' => 'Поле веса груза'
            ],
            [
                'key' => 'ready_date',
                'rus' => 'Дата готовности',
                'kaz' => 'Дайындық күні',
                'chn' => '准备日期',
                'group' => 'cargo',
                'description' => 'Поле даты готовности'
            ],
            [
                'key' => 'comment',
                'rus' => 'Комментарий',
                'kaz' => 'Түсініктеме',
                'chn' => '评论',
                'group' => 'cargo',
                'description' => 'Поле комментария'
            ],
            [
                'key' => 'status',
                'rus' => 'Статус',
                'kaz' => 'Күй',
                'chn' => '状态',
                'group' => 'cargo',
                'description' => 'Поле статуса'
            ],
            [
                'key' => 'available',
                'rus' => 'Доступен',
                'kaz' => 'Қолжетімді',
                'chn' => '可用',
                'group' => 'cargo',
                'description' => 'Статус доступности'
            ],
            [
                'key' => 'in_progress',
                'rus' => 'В работе',
                'kaz' => 'Жұмыс істеп тұр',
                'chn' => '进行中',
                'group' => 'cargo',
                'description' => 'Статус в работе'
            ],
            [
                'key' => 'delivered',
                'rus' => 'Доставлен',
                'kaz' => 'Жеткізілді',
                'chn' => '已交付',
                'group' => 'cargo',
                'description' => 'Статус доставки'
            ],
            
            // Переводы для машин
            [
                'key' => 'car',
                'rus' => 'Машина',
                'kaz' => 'Машина',
                'chn' => '汽车',
                'group' => 'car',
                'description' => 'Название машины'
            ],
            [
                'key' => 'cars.all_cars',
                'rus' => 'Все машины',
                'kaz' => 'Барлық машиналар',
                'chn' => '所有车辆',
                'group' => 'car',
                'description' => 'Заголовок страницы всех машин'
            ],
            [
                'key' => 'cars.all_cars_description',
                'rus' => 'Список всех зарегистрированных машин в системе',
                'kaz' => 'Жүйеде тіркелген барлық машиналар тізімі',
                'chn' => '系统中所有注册车辆的列表',
                'group' => 'car',
                'description' => 'Описание страницы всех машин'
            ],
            [
                'key' => 'cars.add_car',
                'rus' => 'Добавить машину',
                'kaz' => 'Машина қосу',
                'chn' => '添加车辆',
                'group' => 'car',
                'description' => 'Кнопка добавления машины'
            ],
            [
                'key' => 'cars.all_statuses',
                'rus' => 'Все статусы',
                'kaz' => 'Барлық күйлер',
                'chn' => '所有状态',
                'group' => 'car',
                'description' => 'Фильтр всех статусов'
            ],
            [
                'key' => 'cars.filter',
                'rus' => 'Фильтровать',
                'kaz' => 'Сүзгі',
                'chn' => '筛选',
                'group' => 'car',
                'description' => 'Кнопка фильтрации'
            ],
            [
                'key' => 'cars.table_car',
                'rus' => 'Машина',
                'kaz' => 'Машина',
                'chn' => '车辆',
                'group' => 'car',
                'description' => 'Заголовок колонки машины'
            ],
            [
                'key' => 'cars.table_driver',
                'rus' => 'Водитель',
                'kaz' => 'Жүргізуші',
                'chn' => '司机',
                'group' => 'car',
                'description' => 'Заголовок колонки водителя'
            ],
            [
                'key' => 'cars.table_trailer',
                'rus' => 'Прицеп',
                'kaz' => 'Тіркеме',
                'chn' => '拖车',
                'group' => 'car',
                'description' => 'Заголовок колонки прицепа'
            ],
            [
                'key' => 'cars.table_status',
                'rus' => 'Статус',
                'kaz' => 'Күй',
                'chn' => '状态',
                'group' => 'car',
                'description' => 'Заголовок колонки статуса'
            ],
            [
                'key' => 'cars.table_actions',
                'rus' => 'Действия',
                'kaz' => 'Әрекеттер',
                'chn' => '操作',
                'group' => 'car',
                'description' => 'Заголовок колонки действий'
            ],
            [
                'key' => 'cars.status_active',
                'rus' => 'Активна',
                'kaz' => 'Белсенді',
                'chn' => '活跃',
                'group' => 'car',
                'description' => 'Статус активной машины'
            ],
            [
                'key' => 'cars.status_inactive',
                'rus' => 'Неактивна',
                'kaz' => 'Белсенді емес',
                'chn' => '不活跃',
                'group' => 'car',
                'description' => 'Статус неактивной машины'
            ],
            [
                'key' => 'cars.driver',
                'rus' => 'Водитель:',
                'kaz' => 'Жүргізуші:',
                'chn' => '司机:',
                'group' => 'car',
                'description' => 'Поле водителя'
            ],
            [
                'key' => 'cars.email',
                'rus' => 'Email:',
                'kaz' => 'Email:',
                'chn' => '邮箱:',
                'group' => 'car',
                'description' => 'Поле email'
            ],
            [
                'key' => 'cars.trailer',
                'rus' => 'Прицеп:',
                'kaz' => 'Тіркеме:',
                'chn' => '拖车:',
                'group' => 'car',
                'description' => 'Поле прицепа'
            ],
            [
                'key' => 'cars.dimensions',
                'rus' => 'Габариты:',
                'kaz' => 'Өлшемдер:',
                'chn' => '尺寸:',
                'group' => 'car',
                'description' => 'Поле габаритов'
            ],
            [
                'key' => 'cars.view',
                'rus' => 'Просмотр',
                'kaz' => 'Көру',
                'chn' => '查看',
                'group' => 'car',
                'description' => 'Кнопка просмотра'
            ],
            [
                'key' => 'cars.edit',
                'rus' => 'Редактировать',
                'kaz' => 'Өңдеу',
                'chn' => '编辑',
                'group' => 'car',
                'description' => 'Кнопка редактирования'
            ],
            
            // Главная страница
            [
                'key' => 'welcome.title',
                'rus' => 'Silk Way - Система управления грузоперевозками',
                'kaz' => 'Silk Way - Жүк тасымалдау басқару жүйесі',
                'chn' => 'Silk Way - 货运管理系统',
                'group' => 'welcome',
                'description' => 'Заголовок главной страницы'
            ],
            [
                'key' => 'welcome.system_title',
                'rus' => 'Система управления грузоперевозками',
                'kaz' => 'Жүк тасымалдау басқару жүйесі',
                'chn' => '货运管理系统',
                'group' => 'welcome',
                'description' => 'Подзаголовок системы'
            ],
            [
                'key' => 'welcome.cargo_management',
                'rus' => 'Управление грузами',
                'kaz' => 'Жүктерді басқару',
                'chn' => '货物管理',
                'group' => 'welcome',
                'description' => 'Заголовок блока управления грузами'
            ],
            [
                'key' => 'welcome.cargo_management_desc',
                'rus' => 'Создание, редактирование и отслеживание грузов',
                'kaz' => 'Жүктерді құру, өңдеу және қадағалау',
                'chn' => '创建、编辑和跟踪货物',
                'group' => 'welcome',
                'description' => 'Описание управления грузами'
            ],
            [
                'key' => 'welcome.create_applications',
                'rus' => 'Создание заявок на перевозку',
                'kaz' => 'Тасымалдауға өтініштер құру',
                'chn' => '创建运输申请',
                'group' => 'welcome',
                'description' => 'Функция создания заявок'
            ],
            [
                'key' => 'welcome.track_delivery',
                'rus' => 'Отслеживание статуса доставки',
                'kaz' => 'Жеткізу күйін қадағалау',
                'chn' => '跟踪交付状态',
                'group' => 'welcome',
                'description' => 'Функция отслеживания'
            ],
            [
                'key' => 'welcome.route_management',
                'rus' => 'Управление маршрутами',
                'kaz' => 'Маршруттарды басқару',
                'chn' => '路线管理',
                'group' => 'welcome',
                'description' => 'Функция управления маршрутами'
            ],
            [
                'key' => 'welcome.car_management',
                'rus' => 'Управление машинами',
                'kaz' => 'Машиналарды басқару',
                'chn' => '车辆管理',
                'group' => 'welcome',
                'description' => 'Заголовок блока управления машинами'
            ],
            [
                'key' => 'welcome.car_management_desc',
                'rus' => 'Регистрация и управление автопарком водителей',
                'kaz' => 'Жүргізушілер автопаркін тіркеу және басқару',
                'chn' => '司机车队注册和管理',
                'group' => 'welcome',
                'description' => 'Описание управления машинами'
            ],
            [
                'key' => 'welcome.register_cars',
                'rus' => 'Регистрация машин и прицепов',
                'kaz' => 'Машиналар мен тіркемелерді тіркеу',
                'chn' => '车辆和拖车注册',
                'group' => 'welcome',
                'description' => 'Функция регистрации машин'
            ],
            [
                'key' => 'welcome.technical_specs',
                'rus' => 'Учет технических характеристик',
                'kaz' => 'Техникалық сипаттамаларды есепке алу',
                'chn' => '技术规格记录',
                'group' => 'welcome',
                'description' => 'Функция учета характеристик'
            ],
            [
                'key' => 'welcome.upload_docs',
                'rus' => 'Загрузка документов ПДД',
                'kaz' => 'Жол қауіпсіздігі қағидалары құжаттарын жүктеу',
                'chn' => '上传交通规则文件',
                'group' => 'welcome',
                'description' => 'Функция загрузки документов'
            ],
            [
                'key' => 'welcome.user_management',
                'rus' => 'Управление пользователями',
                'kaz' => 'Пайдаланушыларды басқару',
                'chn' => '用户管理',
                'group' => 'welcome',
                'description' => 'Заголовок блока управления пользователями'
            ],
            [
                'key' => 'welcome.user_management_desc',
                'rus' => 'Роли и права доступа в системе',
                'kaz' => 'Жүйедегі рөлдер мен қол жеткізу құқықтары',
                'chn' => '系统中的角色和访问权限',
                'group' => 'welcome',
                'description' => 'Описание управления пользователями'
            ],
            [
                'key' => 'welcome.admins',
                'rus' => 'Администраторы',
                'kaz' => 'Әкімшілер',
                'chn' => '管理员',
                'group' => 'welcome',
                'description' => 'Роль администратора'
            ],
            [
                'key' => 'welcome.warehouse_workers',
                'rus' => 'Складские работники',
                'kaz' => 'Қойма жұмысшылары',
                'chn' => '仓库工人',
                'group' => 'welcome',
                'description' => 'Роль складского работника'
            ],
            [
                'key' => 'welcome.drivers',
                'rus' => 'Водители',
                'kaz' => 'Жүргізушілер',
                'chn' => '司机',
                'group' => 'welcome',
                'description' => 'Роль водителя'
            ],
            [
                'key' => 'welcome.demo_title',
                'rus' => 'Демонстрация системы',
                'kaz' => 'Жүйені көрсету',
                'chn' => '系统演示',
                'group' => 'welcome',
                'description' => 'Заголовок демонстрации'
            ],
            [
                'key' => 'welcome.demo_description',
                'rus' => 'Для тестирования системы используйте следующие учетные данные:',
                'kaz' => 'Жүйені сынау үшін келесі есептік деректерді пайдаланыңыз:',
                'chn' => '要测试系统，请使用以下凭据：',
                'group' => 'welcome',
                'description' => 'Описание демонстрации'
            ],
            [
                'key' => 'welcome.test_drivers',
                'rus' => 'Тестовые водители:',
                'kaz' => 'Сынақ жүргізушілері:',
                'chn' => '测试司机：',
                'group' => 'welcome',
                'description' => 'Заголовок тестовых водителей'
            ],
            
            // Аутентификация
            [
                'key' => 'auth.login',
                'rus' => 'Вход - Silk Way',
                'kaz' => 'Кіру - Silk Way',
                'chn' => '登录 - Silk Way',
                'group' => 'auth',
                'description' => 'Заголовок страницы входа'
            ],
            [
                'key' => 'auth.email_placeholder',
                'rus' => 'Email адрес',
                'kaz' => 'Email мекенжайы',
                'chn' => '邮箱地址',
                'group' => 'auth',
                'description' => 'Плейсхолдер email'
            ],
            [
                'key' => 'auth.password_placeholder',
                'rus' => 'Пароль',
                'kaz' => 'Құпия сөз',
                'chn' => '密码',
                'group' => 'auth',
                'description' => 'Плейсхолдер пароля'
            ],
            [
                'key' => 'auth.login_button',
                'rus' => 'Войти',
                'kaz' => 'Кіру',
                'chn' => '登录',
                'group' => 'auth',
                'description' => 'Кнопка входа'
            ],
            [
                'key' => 'auth.no_account',
                'rus' => 'Нет аккаунта?',
                'kaz' => 'Есептік жазба жоқ па?',
                'chn' => '没有账户？',
                'group' => 'auth',
                'description' => 'Текст о отсутствии аккаунта'
            ],
            [
                'key' => 'auth.register_link',
                'rus' => 'Зарегистрироваться',
                'kaz' => 'Тіркелу',
                'chn' => '注册',
                'group' => 'auth',
                'description' => 'Ссылка на регистрацию'
            ],
            [
                'key' => 'auth.register_title',
                'rus' => 'Регистрация - Silk Way',
                'kaz' => 'Тіркеу - Silk Way',
                'chn' => '注册 - Silk Way',
                'group' => 'auth',
                'description' => 'Заголовок страницы регистрации'
            ],
            [
                'key' => 'auth.register_heading',
                'rus' => 'Регистрация',
                'kaz' => 'Тіркеу',
                'chn' => '注册',
                'group' => 'auth',
                'description' => 'Заголовок формы регистрации'
            ],
            [
                'key' => 'auth.register_desc',
                'rus' => 'Создайте аккаунт для работы в системе',
                'kaz' => 'Жүйеде жұмыс істеу үшін есептік жазба құрыңыз',
                'chn' => '创建账户以在系统中工作',
                'group' => 'auth',
                'description' => 'Описание формы регистрации'
            ],
            [
                'key' => 'auth.full_name',
                'rus' => 'Полное имя',
                'kaz' => 'Толық аты',
                'chn' => '全名',
                'group' => 'auth',
                'description' => 'Плейсхолдер полного имени'
            ],
            [
                'key' => 'auth.password_confirmation',
                'rus' => 'Подтвердите пароль',
                'kaz' => 'Құпия сөзді растаңыз',
                'chn' => '确认密码',
                'group' => 'auth',
                'description' => 'Плейсхолдер подтверждения пароля'
            ],
            [
                'key' => 'auth.select_role',
                'rus' => 'Выберите роль',
                'kaz' => 'Рөлді таңдаңыз',
                'chn' => '选择角色',
                'group' => 'auth',
                'description' => 'Плейсхолдер выбора роли'
            ],
            [
                'key' => 'auth.warehouse_employee',
                'rus' => 'Сотрудник склада',
                'kaz' => 'Қойма қызметкері',
                'chn' => '仓库员工',
                'group' => 'auth',
                'description' => 'Роль сотрудника склада'
            ],
            [
                'key' => 'auth.driver_role',
                'rus' => 'Водитель',
                'kaz' => 'Жүргізуші',
                'chn' => '司机',
                'group' => 'auth',
                'description' => 'Роль водителя'
            ],
            [
                'key' => 'auth.register_button',
                'rus' => 'Зарегистрироваться',
                'kaz' => 'Тіркелу',
                'chn' => '注册',
                'group' => 'auth',
                'description' => 'Кнопка регистрации'
            ],
            [
                'key' => 'auth.have_account',
                'rus' => 'Уже есть аккаунт?',
                'kaz' => 'Есептік жазба бар ма?',
                'chn' => '已有账户？',
                'group' => 'auth',
                'description' => 'Текст о наличии аккаунта'
            ],
            [
                'key' => 'auth.login_link',
                'rus' => 'Войти',
                'kaz' => 'Кіру',
                'chn' => '登录',
                'group' => 'auth',
                'description' => 'Ссылка на вход'
            ],
            
            // Страницы грузов
            [
                'key' => 'cargo.available_cargo',
                'rus' => 'Доступные грузы',
                'kaz' => 'Қолжетімді жүктер',
                'chn' => '可用货物',
                'group' => 'cargo',
                'description' => 'Заголовок страницы доступных грузов'
            ],
            [
                'key' => 'cargo.available_cargo_desc',
                'rus' => 'Список всех доступных для перевозки грузов',
                'kaz' => 'Тасымалдауға қолжетімді барлық жүктер тізімі',
                'chn' => '所有可用于运输的货物列表',
                'group' => 'cargo',
                'description' => 'Описание страницы доступных грузов'
            ],
            [
                'key' => 'cargo.add_cargo',
                'rus' => 'Добавить груз',
                'kaz' => 'Жүк қосу',
                'chn' => '添加货物',
                'group' => 'cargo',
                'description' => 'Кнопка добавления груза'
            ],
            [
                'key' => 'cargo.search_placeholder',
                'rus' => 'Поиск по маршруту или типу груза...',
                'kaz' => 'Маршрут немесе жүк түрі бойынша іздеу...',
                'chn' => '按路线或货物类型搜索...',
                'group' => 'cargo',
                'description' => 'Плейсхолдер поиска грузов'
            ],
            [
                'key' => 'cargo.all_statuses',
                'rus' => 'Все статусы',
                'kaz' => 'Барлық күйлер',
                'chn' => '所有状态',
                'group' => 'cargo',
                'description' => 'Фильтр всех статусов грузов'
            ],
            [
                'key' => 'cargo.status_available',
                'rus' => 'Доступен',
                'kaz' => 'Қолжетімді',
                'chn' => '可用',
                'group' => 'cargo',
                'description' => 'Статус доступности груза'
            ],
            [
                'key' => 'cargo.status_picked_up',
                'rus' => 'Забран',
                'kaz' => 'Алынды',
                'chn' => '已取',
                'group' => 'cargo',
                'description' => 'Статус забранного груза'
            ],
            [
                'key' => 'cargo.status_delivered',
                'rus' => 'Доставлен',
                'kaz' => 'Жеткізілді',
                'chn' => '已交付',
                'group' => 'cargo',
                'description' => 'Статус доставленного груза'
            ],
            [
                'key' => 'cargo.table_route',
                'rus' => 'Маршрут',
                'kaz' => 'Маршрут',
                'chn' => '路线',
                'group' => 'cargo',
                'description' => 'Заголовок колонки маршрута'
            ],
            [
                'key' => 'cargo.table_cargo',
                'rus' => 'Груз',
                'kaz' => 'Жүк',
                'chn' => '货物',
                'group' => 'cargo',
                'description' => 'Заголовок колонки груза'
            ],
            [
                'key' => 'cargo.table_readiness',
                'rus' => 'Готовность',
                'kaz' => 'Дайындық',
                'chn' => '准备就绪',
                'group' => 'cargo',
                'description' => 'Заголовок колонки готовности'
            ],
            [
                'key' => 'cargo.table_status',
                'rus' => 'Статус',
                'kaz' => 'Күй',
                'chn' => '状态',
                'group' => 'cargo',
                'description' => 'Заголовок колонки статуса'
            ],
            [
                'key' => 'cargo.table_created',
                'rus' => 'Создан',
                'kaz' => 'Құрылды',
                'chn' => '已创建',
                'group' => 'cargo',
                'description' => 'Заголовок колонки создания'
            ],
            [
                'key' => 'cargo.table_actions',
                'rus' => 'Действия',
                'kaz' => 'Әрекеттер',
                'chn' => '操作',
                'group' => 'cargo',
                'description' => 'Заголовок колонки действий'
            ],
            [
                'key' => 'cargo.no_cargo_found',
                'rus' => 'Грузы не найдены',
                'kaz' => 'Жүктер табылмады',
                'chn' => '未找到货物',
                'group' => 'cargo',
                'description' => 'Сообщение об отсутствии грузов'
            ],
            [
                'key' => 'cargo.no_cargo_desc',
                'rus' => 'В данный момент нет доступных для перевозки грузов',
                'kaz' => 'Қазіргі уақытта тасымалдауға қолжетімді жүктер жоқ',
                'chn' => '目前没有可用于运输的货物',
                'group' => 'cargo',
                'description' => 'Описание отсутствия грузов'
            ],
            [
                'key' => 'cargo.change_search',
                'rus' => 'Попробуйте изменить параметры поиска',
                'kaz' => 'Іздеу параметрлерін өзгертуге тырысыңыз',
                'chn' => '尝试更改搜索参数',
                'group' => 'cargo',
                'description' => 'Совет по изменению поиска'
            ],
            [
                'key' => 'cargo.reset_filters',
                'rus' => 'Сбросить фильтры',
                'kaz' => 'Сүзгілерді қалпына келтіру',
                'chn' => '重置筛选器',
                'group' => 'cargo',
                'description' => 'Кнопка сброса фильтров'
            ],
            
            // Админ панель
            [
                'key' => 'admin.dashboard_title',
                'rus' => 'Админ-панель',
                'kaz' => 'Әкімші панелі',
                'chn' => '管理面板',
                'group' => 'admin',
                'description' => 'Заголовок админ панели'
            ],
            [
                'key' => 'admin.dashboard_desc',
                'rus' => 'Управление системой и пользователями',
                'kaz' => 'Жүйе мен пайдаланушыларды басқару',
                'chn' => '系统和用户管理',
                'group' => 'admin',
                'description' => 'Описание админ панели'
            ],
            [
                'key' => 'admin.total_cargo',
                'rus' => 'Всего грузов',
                'kaz' => 'Барлығы жүктер',
                'chn' => '货物总数',
                'group' => 'admin',
                'description' => 'Статистика общего количества грузов'
            ],
            [
                'key' => 'admin.available_cargo',
                'rus' => 'Доступные грузы',
                'kaz' => 'Қолжетімді жүктер',
                'chn' => '可用货物',
                'group' => 'admin',
                'description' => 'Статистика доступных грузов'
            ],
            [
                'key' => 'admin.picked_up_cargo',
                'rus' => 'Забранные грузы',
                'kaz' => 'Алынған жүктер',
                'chn' => '已取货物',
                'group' => 'admin',
                'description' => 'Статистика забранных грузов'
            ],
            [
                'key' => 'admin.pending_users',
                'rus' => 'Пользователи на подтверждение',
                'kaz' => 'Бекіту күтудегі пайдаланушылар',
                'chn' => '等待确认的用户',
                'group' => 'admin',
                'description' => 'Заголовок блока ожидающих пользователей'
            ],
            [
                'key' => 'admin.pending_users_desc',
                'rus' => 'Подтвердите или отклоните заявки на регистрацию',
                'kaz' => 'Тіркеу өтініштерін бекітіңіз немесе бас тартыңыз',
                'chn' => '确认或拒绝注册申请',
                'group' => 'admin',
                'description' => 'Описание блока ожидающих пользователей'
            ],
            [
                'key' => 'admin.approved_users',
                'rus' => 'Подтвержденные пользователи',
                'kaz' => 'Бекітілген пайдаланушылар',
                'chn' => '已确认用户',
                'group' => 'admin',
                'description' => 'Заголовок блока подтвержденных пользователей'
            ],
            [
                'key' => 'admin.approved_users_desc',
                'rus' => 'Активные пользователи системы',
                'kaz' => 'Жүйенің белсенді пайдаланушылары',
                'chn' => '系统的活跃用户',
                'group' => 'admin',
                'description' => 'Описание блока подтвержденных пользователей'
            ],
            [
                'key' => 'admin.approve',
                'rus' => 'Подтвердить',
                'kaz' => 'Бекіту',
                'chn' => '确认',
                'group' => 'admin',
                'description' => 'Кнопка подтверждения пользователя'
            ],
            [
                'key' => 'admin.reject',
                'rus' => 'Отклонить',
                'kaz' => 'Бас тарту',
                'chn' => '拒绝',
                'group' => 'admin',
                'description' => 'Кнопка отклонения пользователя'
            ],
            [
                'key' => 'admin.toggle_approval',
                'rus' => 'Отозвать доступ',
                'kaz' => 'Қол жеткізуді алып тастау',
                'chn' => '撤销访问权限',
                'group' => 'admin',
                'description' => 'Кнопка отзыва доступа'
            ],
            [
                'key' => 'admin.translations_button',
                'rus' => 'Переводы',
                'kaz' => 'Аудармалар',
                'chn' => '翻译',
                'group' => 'admin',
                'description' => 'Кнопка управления переводами'
            ],
            [
                'key' => 'admin.user_name',
                'rus' => 'Имя',
                'kaz' => 'Аты',
                'chn' => '姓名',
                'group' => 'admin',
                'description' => 'Заголовок колонки имени пользователя'
            ],
            [
                'key' => 'admin.user_email',
                'rus' => 'Email',
                'kaz' => 'Email',
                'chn' => '邮箱',
                'group' => 'admin',
                'description' => 'Заголовок колонки email пользователя'
            ],
            [
                'key' => 'admin.user_role',
                'rus' => 'Роль',
                'kaz' => 'Рөл',
                'chn' => '角色',
                'group' => 'admin',
                'description' => 'Заголовок колонки роли пользователя'
            ],
            [
                'key' => 'admin.user_actions',
                'rus' => 'Действия',
                'kaz' => 'Әрекеттер',
                'chn' => '操作',
                'group' => 'admin',
                'description' => 'Заголовок колонки действий'
            ],
            [
                'key' => 'admin.administrator',
                'rus' => 'Администратор',
                'kaz' => 'Әкімші',
                'chn' => '管理员',
                'group' => 'admin',
                'description' => 'Роль администратора'
            ],
            [
                'key' => 'admin.registered_at',
                'rus' => 'Зарегистрирован',
                'kaz' => 'Тіркелген',
                'chn' => '已注册',
                'group' => 'admin',
                'description' => 'Текст о времени регистрации'
            ],
            [
                'key' => 'admin.approved_at',
                'rus' => 'Подтвержден',
                'kaz' => 'Бекітілген',
                'chn' => '已确认',
                'group' => 'admin',
                'description' => 'Текст о времени подтверждения'
            ],
            [
                'key' => 'admin.confirm_reject_user',
                'rus' => 'Отклонить этого пользователя?',
                'kaz' => 'Бұл пайдаланушыны бас тарту керек пе?',
                'chn' => '拒绝这个用户？',
                'group' => 'admin',
                'description' => 'Подтверждение отклонения пользователя'
            ],
            [
                'key' => 'admin.confirm_delete_user',
                'rus' => 'Удалить этого пользователя?',
                'kaz' => 'Бұл пайдаланушыны жою керек пе?',
                'chn' => '删除这个用户？',
                'group' => 'admin',
                'description' => 'Подтверждение удаления пользователя'
            ],
            [
                'key' => 'admin.users_management_title',
                'rus' => 'Управление пользователями - Silk Way',
                'kaz' => 'Пайдаланушыларды басқару - Silk Way',
                'chn' => '用户管理 - Silk Way',
                'group' => 'admin',
                'description' => 'Заголовок страницы управления пользователями'
            ],
            [
                'key' => 'admin.users_management_heading',
                'rus' => 'Управление пользователями',
                'kaz' => 'Пайдаланушыларды басқару',
                'chn' => '用户管理',
                'group' => 'admin',
                'description' => 'Заголовок страницы управления пользователями'
            ],
            [
                'key' => 'admin.users_management_desc',
                'rus' => 'Просмотр и управление всеми пользователями системы',
                'kaz' => 'Жүйедегі барлық пайдаланушыларды көру және басқару',
                'chn' => '查看和管理系统中的所有用户',
                'group' => 'admin',
                'description' => 'Описание страницы управления пользователями'
            ],
            [
                'key' => 'admin.table_user',
                'rus' => 'Пользователь',
                'kaz' => 'Пайдаланушы',
                'chn' => '用户',
                'group' => 'admin',
                'description' => 'Заголовок колонки пользователя'
            ],
            [
                'key' => 'admin.table_role',
                'rus' => 'Роль',
                'kaz' => 'Рөл',
                'chn' => '角色',
                'group' => 'admin',
                'description' => 'Заголовок колонки роли'
            ],
            [
                'key' => 'admin.table_status',
                'rus' => 'Статус',
                'kaz' => 'Күй',
                'chn' => '状态',
                'group' => 'admin',
                'description' => 'Заголовок колонки статуса'
            ],
            [
                'key' => 'admin.table_registration_date',
                'rus' => 'Дата регистрации',
                'kaz' => 'Тіркелу күні',
                'chn' => '注册日期',
                'group' => 'admin',
                'description' => 'Заголовок колонки даты регистрации'
            ],
            [
                'key' => 'admin.status_approved',
                'rus' => 'Подтвержден',
                'kaz' => 'Бекітілген',
                'chn' => '已确认',
                'group' => 'admin',
                'description' => 'Статус подтвержденного пользователя'
            ],
            [
                'key' => 'admin.status_pending',
                'rus' => 'Ожидает подтверждения',
                'kaz' => 'Бекітуді күтуде',
                'chn' => '等待确认',
                'group' => 'admin',
                'description' => 'Статус пользователя, ожидающего подтверждения'
            ],
            [
                'key' => 'admin.toggle_access_title',
                'rus' => 'Отозвать доступ',
                'kaz' => 'Қол жеткізуді алып тастау',
                'chn' => '撤销访问权限',
                'group' => 'admin',
                'description' => 'Тултип кнопки отзыва доступа'
            ],
            [
                'key' => 'admin.delete_user_title',
                'rus' => 'Удалить пользователя',
                'kaz' => 'Пайдаланушыны жою',
                'chn' => '删除用户',
                'group' => 'admin',
                'description' => 'Тултип кнопки удаления пользователя'
            ],
            [
                'key' => 'cargo.add_cargo_button',
                'rus' => 'Добавить груз',
                'kaz' => 'Жүк қосу',
                'chn' => '添加货物',
                'group' => 'cargo',
                'description' => 'Кнопка добавления груза'
            ],
            [
                'key' => 'cargo.search_placeholder',
                'rus' => 'Поиск по маршруту или типу груза...',
                'kaz' => 'Маршрут немесе жүк түрі бойынша іздеу...',
                'chn' => '按路线或货物类型搜索...',
                'group' => 'cargo',
                'description' => 'Плейсхолдер поиска грузов'
            ],
            [
                'key' => 'cargo.all_statuses',
                'rus' => 'Все статусы',
                'kaz' => 'Барлық күйлер',
                'chn' => '所有状态',
                'group' => 'cargo',
                'description' => 'Фильтр всех статусов грузов'
            ],
            [
                'key' => 'cargo.status_available',
                'rus' => 'Доступен',
                'kaz' => 'Қолжетімді',
                'chn' => '可用',
                'group' => 'cargo',
                'description' => 'Статус доступности груза'
            ],
            [
                'key' => 'cargo.status_picked_up',
                'rus' => 'Забран',
                'kaz' => 'Алынған',
                'chn' => '已取',
                'group' => 'cargo',
                'description' => 'Статус забранного груза'
            ],
            [
                'key' => 'cargo.status_delivered',
                'rus' => 'Доставлен',
                'kaz' => 'Жеткізілген',
                'chn' => '已送达',
                'group' => 'cargo',
                'description' => 'Статус доставленного груза'
            ],
            [
                'key' => 'cargo.filter_button',
                'rus' => 'Фильтровать',
                'kaz' => 'Сүзгілеу',
                'chn' => '筛选',
                'group' => 'cargo',
                'description' => 'Кнопка фильтрации'
            ],
            [
                'key' => 'cargo.table_route',
                'rus' => 'Маршрут',
                'kaz' => 'Маршрут',
                'chn' => '路线',
                'group' => 'cargo',
                'description' => 'Заголовок колонки маршрута'
            ],
            [
                'key' => 'cargo.table_cargo',
                'rus' => 'Груз',
                'kaz' => 'Жүк',
                'chn' => '货物',
                'group' => 'cargo',
                'description' => 'Заголовок колонки груза'
            ],
            [
                'key' => 'cargo.table_readiness',
                'rus' => 'Готовность',
                'kaz' => 'Дайындық',
                'chn' => '准备状态',
                'group' => 'cargo',
                'description' => 'Заголовок колонки готовности'
            ],
            [
                'key' => 'cargo.table_status',
                'rus' => 'Статус',
                'kaz' => 'Күй',
                'chn' => '状态',
                'group' => 'cargo',
                'description' => 'Заголовок колонки статуса'
            ],
            [
                'key' => 'cargo.table_created',
                'rus' => 'Создан',
                'kaz' => 'Құрылған',
                'chn' => '创建时间',
                'group' => 'cargo',
                'description' => 'Заголовок колонки создания'
            ],
            [
                'key' => 'cargo.volume_weight',
                'rus' => 'м³, кг',
                'kaz' => 'м³, кг',
                'chn' => '立方米, 公斤',
                'group' => 'cargo',
                'description' => 'Единицы измерения объема и веса'
            ],
            [
                'key' => 'cargo.view_button',
                'rus' => 'Просмотр',
                'kaz' => 'Көру',
                'chn' => '查看',
                'group' => 'cargo',
                'description' => 'Кнопка просмотра груза'
            ],
            [
                'key' => 'cargo.no_cargo_found',
                'rus' => 'Грузы не найдены',
                'kaz' => 'Жүктер табылмады',
                'chn' => '未找到货物',
                'group' => 'cargo',
                'description' => 'Заголовок при отсутствии грузов'
            ],
            [
                'key' => 'cargo.no_cargo_desc',
                'rus' => 'В данный момент нет доступных грузов для перевозки',
                'kaz' => 'Қазіргі уақытта тасымалдауға қолжетімді жүктер жоқ',
                'chn' => '目前没有可运输的货物',
                'group' => 'cargo',
                'description' => 'Описание при отсутствии грузов'
            ],
            [
                'key' => 'cargo.try_change_search',
                'rus' => 'Попробуйте изменить параметры поиска',
                'kaz' => 'Іздеу параметрлерін өзгертуге тырысыңыз',
                'chn' => '尝试更改搜索参数',
                'group' => 'cargo',
                'description' => 'Совет при неудачном поиске'
            ],
            [
                'key' => 'cargo.reset_filters',
                'rus' => 'Сбросить фильтры',
                'kaz' => 'Сүзгілерді қалпына келтіру',
                'chn' => '重置筛选器',
                'group' => 'cargo',
                'description' => 'Кнопка сброса фильтров'
            ],
            [
                'key' => 'cargo.confirm_delete',
                'rus' => 'Удалить этот груз?',
                'kaz' => 'Бұл жүкті жою керек пе?',
                'chn' => '删除这个货物？',
                'group' => 'cargo',
                'description' => 'Подтверждение удаления груза'
            ],
            [
                'key' => 'cargo.volume_label',
                'rus' => 'Объем:',
                'kaz' => 'Көлемі:',
                'chn' => '体积：',
                'group' => 'cargo',
                'description' => 'Метка объема в мобильной карточке'
            ],
            [
                'key' => 'cargo.weight_label',
                'rus' => 'Вес:',
                'kaz' => 'Салмағы:',
                'chn' => '重量：',
                'group' => 'cargo',
                'description' => 'Метка веса в мобильной карточке'
            ],
            [
                'key' => 'cargo.readiness_label',
                'rus' => 'Готовность:',
                'kaz' => 'Дайындық:',
                'chn' => '准备状态：',
                'group' => 'cargo',
                'description' => 'Метка готовности в мобильной карточке'
            ],
            [
                'key' => 'cargo.created_label',
                'rus' => 'Создан:',
                'kaz' => 'Құрылған:',
                'chn' => '创建时间：',
                'group' => 'cargo',
                'description' => 'Метка создания в мобильной карточке'
            ],
            [
                'key' => 'applications.title',
                'rus' => 'Заявки на грузы',
                'kaz' => 'Жүктерге өтініштер',
                'chn' => '货物申请',
                'group' => 'applications',
                'description' => 'Заголовок страницы заявок'
            ],
            [
                'key' => 'applications.heading',
                'rus' => 'Заявки на грузы',
                'kaz' => 'Жүктерге өтініштер',
                'chn' => '货物申请',
                'group' => 'applications',
                'description' => 'Основной заголовок страницы заявок'
            ],
            [
                'key' => 'applications.admin_desc',
                'rus' => 'Все заявки в системе',
                'kaz' => 'Жүйедегі барлық өтініштер',
                'chn' => '系统中的所有申请',
                'group' => 'applications',
                'description' => 'Описание для администраторов'
            ],
            [
                'key' => 'applications.driver_desc',
                'rus' => 'Заявки на ваши грузы',
                'kaz' => 'Сіздің жүктеріңізге өтініштер',
                'chn' => '您货物的申请',
                'group' => 'applications',
                'description' => 'Описание для водителей'
            ],
            [
                'key' => 'applications.status_label',
                'rus' => 'Статус',
                'kaz' => 'Күй',
                'chn' => '状态',
                'group' => 'applications',
                'description' => 'Метка поля статуса'
            ],
            [
                'key' => 'applications.all_statuses',
                'rus' => 'Все статусы',
                'kaz' => 'Барлық күйлер',
                'chn' => '所有状态',
                'group' => 'applications',
                'description' => 'Фильтр всех статусов'
            ],
            [
                'key' => 'applications.status_pending',
                'rus' => 'Ожидает рассмотрения',
                'kaz' => 'Қарастыруды күтуде',
                'chn' => '等待审核',
                'group' => 'applications',
                'description' => 'Статус ожидания рассмотрения'
            ],
            [
                'key' => 'applications.status_approved',
                'rus' => 'Подтверждена',
                'kaz' => 'Бекітілген',
                'chn' => '已确认',
                'group' => 'applications',
                'description' => 'Статус подтверждения'
            ],
            [
                'key' => 'applications.status_rejected',
                'rus' => 'Отклонена',
                'kaz' => 'Бас тартылған',
                'chn' => '已拒绝',
                'group' => 'applications',
                'description' => 'Статус отклонения'
            ],
            [
                'key' => 'applications.search_label',
                'rus' => 'Поиск',
                'kaz' => 'Іздеу',
                'chn' => '搜索',
                'group' => 'applications',
                'description' => 'Метка поля поиска'
            ],
            [
                'key' => 'applications.search_placeholder',
                'rus' => 'Поиск по маршруту или водителю',
                'kaz' => 'Маршрут немесе жүргізуші бойынша іздеу',
                'chn' => '按路线或司机搜索',
                'group' => 'applications',
                'description' => 'Плейсхолдер поиска'
            ],
            [
                'key' => 'applications.search_button',
                'rus' => 'Поиск',
                'kaz' => 'Іздеу',
                'chn' => '搜索',
                'group' => 'applications',
                'description' => 'Кнопка поиска'
            ],
            [
                'key' => 'applications.table_route',
                'rus' => 'Маршрут',
                'kaz' => 'Маршрут',
                'chn' => '路线',
                'group' => 'applications',
                'description' => 'Заголовок колонки маршрута'
            ],
            [
                'key' => 'applications.table_driver',
                'rus' => 'Водитель',
                'kaz' => 'Жүргізуші',
                'chn' => '司机',
                'group' => 'applications',
                'description' => 'Заголовок колонки водителя'
            ],
            [
                'key' => 'applications.table_status',
                'rus' => 'Статус',
                'kaz' => 'Күй',
                'chn' => '状态',
                'group' => 'applications',
                'description' => 'Заголовок колонки статуса'
            ],
            [
                'key' => 'applications.table_submitted',
                'rus' => 'Подана',
                'kaz' => 'Берілген',
                'chn' => '提交时间',
                'group' => 'applications',
                'description' => 'Заголовок колонки подачи'
            ],
            [
                'key' => 'applications.table_actions',
                'rus' => 'Действия',
                'kaz' => 'Әрекеттер',
                'chn' => '操作',
                'group' => 'applications',
                'description' => 'Заголовок колонки действий'
            ],
            [
                'key' => 'applications.status_pending_short',
                'rus' => 'Ожидает',
                'kaz' => 'Күтуде',
                'chn' => '等待中',
                'group' => 'applications',
                'description' => 'Короткий статус ожидания'
            ],
            [
                'key' => 'applications.status_approved_short',
                'rus' => 'Подтверждена',
                'kaz' => 'Бекітілген',
                'chn' => '已确认',
                'group' => 'applications',
                'description' => 'Короткий статус подтверждения'
            ],
            [
                'key' => 'applications.status_rejected_short',
                'rus' => 'Отклонена',
                'kaz' => 'Бас тартылған',
                'chn' => '已拒绝',
                'group' => 'applications',
                'description' => 'Короткий статус отклонения'
            ],
            [
                'key' => 'applications.view_details',
                'rus' => 'Подробнее',
                'kaz' => 'Толығырақ',
                'chn' => '详情',
                'group' => 'applications',
                'description' => 'Кнопка просмотра деталей'
            ],
            [
                'key' => 'applications.approve_button',
                'rus' => 'Подтвердить',
                'kaz' => 'Бекіту',
                'chn' => '确认',
                'group' => 'applications',
                'description' => 'Кнопка подтверждения'
            ],
            [
                'key' => 'applications.reject_button',
                'rus' => 'Отклонить',
                'kaz' => 'Бас тарту',
                'chn' => '拒绝',
                'group' => 'applications',
                'description' => 'Кнопка отклонения'
            ],
            [
                'key' => 'applications.driver_label',
                'rus' => 'Водитель:',
                'kaz' => 'Жүргізуші:',
                'chn' => '司机：',
                'group' => 'applications',
                'description' => 'Метка водителя в мобильной карточке'
            ],
            [
                'key' => 'applications.submitted_label',
                'rus' => 'Подана:',
                'kaz' => 'Берілген:',
                'chn' => '提交时间：',
                'group' => 'applications',
                'description' => 'Метка времени подачи'
            ],
            [
                'key' => 'applications.confirm_approve',
                'rus' => 'Подтвердить заявку этого водителя?',
                'kaz' => 'Бұл жүргізушінің өтінішін бекіту керек пе?',
                'chn' => '确认这个司机的申请？',
                'group' => 'applications',
                'description' => 'Подтверждение заявки водителя'
            ],
            [
                'key' => 'applications.confirm_reject',
                'rus' => 'Отклонить заявку этого водителя?',
                'kaz' => 'Бұл жүргізушінің өтінішін бас тарту керек пе?',
                'chn' => '拒绝这个司机的申请？',
                'group' => 'applications',
                'description' => 'Подтверждение отклонения заявки'
            ],
            [
                'key' => 'applications.no_applications',
                'rus' => 'Заявок пока нет',
                'kaz' => 'Әзірше өтініштер жоқ',
                'chn' => '暂无申请',
                'group' => 'applications',
                'description' => 'Заголовок при отсутствии заявок'
            ],
            [
                'key' => 'applications.no_applications_desc',
                'rus' => 'Когда водители будут подавать заявки на грузы, они появятся здесь',
                'kaz' => 'Жүргізушілер жүктерге өтініш бергенде, олар осында пайда болады',
                'chn' => '当司机申请货物时，它们将出现在这里',
                'group' => 'applications',
                'description' => 'Описание при отсутствии заявок'
            ],
            [
                'key' => 'applications.back_button',
                'rus' => 'Назад',
                'kaz' => 'Артқа',
                'chn' => '返回',
                'group' => 'applications',
                'description' => 'Кнопка возврата'
            ],
            [
                'key' => 'my_cargo.title',
                'rus' => 'Мои грузы',
                'kaz' => 'Менің жүктерім',
                'chn' => '我的货物',
                'group' => 'my_cargo',
                'description' => 'Заголовок страницы моих грузов'
            ],
            [
                'key' => 'my_cargo.heading',
                'rus' => 'Мои грузы',
                'kaz' => 'Менің жүктерім',
                'chn' => '我的货物',
                'group' => 'my_cargo',
                'description' => 'Основной заголовок страницы моих грузов'
            ],
            [
                'key' => 'my_cargo.description',
                'rus' => 'Грузы, которые вы забрали для доставки',
                'kaz' => 'Сіз жеткізу үшін алған жүктер',
                'chn' => '您已取走用于交付的货物',
                'group' => 'my_cargo',
                'description' => 'Описание страницы моих грузов'
            ],
            [
                'key' => 'my_cargo.back_button',
                'rus' => 'Назад',
                'kaz' => 'Артқа',
                'chn' => '返回',
                'group' => 'my_cargo',
                'description' => 'Кнопка возврата'
            ],
            [
                'key' => 'my_cargo.table_route',
                'rus' => 'Маршрут',
                'kaz' => 'Маршрут',
                'chn' => '路线',
                'group' => 'my_cargo',
                'description' => 'Заголовок колонки маршрута'
            ],
            [
                'key' => 'my_cargo.table_cargo',
                'rus' => 'Груз',
                'kaz' => 'Жүк',
                'chn' => '货物',
                'group' => 'my_cargo',
                'description' => 'Заголовок колонки груза'
            ],
            [
                'key' => 'my_cargo.table_picked',
                'rus' => 'Забран',
                'kaz' => 'Алынған',
                'chn' => '已取',
                'group' => 'my_cargo',
                'description' => 'Заголовок колонки времени забора'
            ],
            [
                'key' => 'my_cargo.table_status',
                'rus' => 'Статус',
                'kaz' => 'Күй',
                'chn' => '状态',
                'group' => 'my_cargo',
                'description' => 'Заголовок колонки статуса'
            ],
            [
                'key' => 'my_cargo.status_in_delivery',
                'rus' => 'В доставке',
                'kaz' => 'Жеткізуде',
                'chn' => '运输中',
                'group' => 'my_cargo',
                'description' => 'Статус груза в доставке'
            ],
            [
                'key' => 'my_cargo.status_delivered',
                'rus' => 'Доставлен',
                'kaz' => 'Жеткізілген',
                'chn' => '已送达',
                'group' => 'my_cargo',
                'description' => 'Статус доставленного груза'
            ],
            [
                'key' => 'my_cargo.mark_delivered',
                'rus' => 'Доставлен',
                'kaz' => 'Жеткізілген',
                'chn' => '已送达',
                'group' => 'my_cargo',
                'description' => 'Кнопка отметки доставки'
            ],
            [
                'key' => 'my_cargo.confirm_mark_delivered',
                'rus' => 'Отметить груз как доставленный?',
                'kaz' => 'Жүкті жеткізілген деп белгілеу керек пе?',
                'chn' => '将货物标记为已送达？',
                'group' => 'my_cargo',
                'description' => 'Подтверждение отметки доставки'
            ],
            [
                'key' => 'my_cargo.view_button',
                'rus' => 'Просмотр',
                'kaz' => 'Көру',
                'chn' => '查看',
                'group' => 'my_cargo',
                'description' => 'Кнопка просмотра груза'
            ],
            [
                'key' => 'my_cargo.volume_label',
                'rus' => 'Объем:',
                'kaz' => 'Көлемі:',
                'chn' => '体积：',
                'group' => 'my_cargo',
                'description' => 'Метка объема в мобильной карточке'
            ],
            [
                'key' => 'my_cargo.weight_label',
                'rus' => 'Вес:',
                'kaz' => 'Салмағы:',
                'chn' => '重量：',
                'group' => 'my_cargo',
                'description' => 'Метка веса в мобильной карточке'
            ],
            [
                'key' => 'my_cargo.picked_label',
                'rus' => 'Забран:',
                'kaz' => 'Алынған:',
                'chn' => '已取：',
                'group' => 'my_cargo',
                'description' => 'Метка времени забора'
            ],
            [
                'key' => 'my_cargo.no_cargo_title',
                'rus' => 'У вас пока нет забранных грузов',
                'kaz' => 'Сізде әзірше алынған жүктер жоқ',
                'chn' => '您目前没有已取的货物',
                'group' => 'my_cargo',
                'description' => 'Заголовок при отсутствии грузов'
            ],
            [
                'key' => 'my_cargo.no_cargo_desc',
                'rus' => 'Заберите груз для доставки, чтобы он появился в этом списке',
                'kaz' => 'Жеткізуге жүк алыңыз, ол осы тізімде пайда болады',
                'chn' => '取走货物进行交付，它将出现在此列表中',
                'group' => 'my_cargo',
                'description' => 'Описание при отсутствии грузов'
            ],
            [
                'key' => 'my_cargo.stats_total_picked',
                'rus' => 'Всего забрано',
                'kaz' => 'Барлығы алынған',
                'chn' => '总共已取',
                'group' => 'my_cargo',
                'description' => 'Статистика: общее количество забранных грузов'
            ],
            [
                'key' => 'my_cargo.stats_in_delivery',
                'rus' => 'В доставке',
                'kaz' => 'Жеткізуде',
                'chn' => '运输中',
                'group' => 'my_cargo',
                'description' => 'Статистика: грузы в доставке'
            ],
            [
                'key' => 'my_cargo.stats_delivered',
                'rus' => 'Доставлено',
                'kaz' => 'Жеткізілген',
                'chn' => '已送达',
                'group' => 'my_cargo',
                'description' => 'Статистика: доставленные грузы'
            ],
            [
                'key' => 'my_applications.title',
                'rus' => 'Мои заявки',
                'kaz' => 'Менің өтініштерім',
                'chn' => '我的申请',
                'group' => 'my_applications',
                'description' => 'Заголовок страницы моих заявок'
            ],
            [
                'key' => 'my_applications.heading',
                'rus' => 'Мои заявки',
                'kaz' => 'Менің өтініштерім',
                'chn' => '我的申请',
                'group' => 'my_applications',
                'description' => 'Основной заголовок страницы моих заявок'
            ],
            [
                'key' => 'my_applications.description',
                'rus' => 'Отслеживайте статус ваших заявок на перевозку грузов',
                'kaz' => 'Жүктерді тасымалдауға арналған өтініштеріңіздің күйін қадағалаңыз',
                'chn' => '跟踪您货物运输申请的状态',
                'group' => 'my_applications',
                'description' => 'Описание страницы моих заявок'
            ],
            [
                'key' => 'my_applications.view_cargo_button',
                'rus' => 'Посмотреть грузы',
                'kaz' => 'Жүктерді көру',
                'chn' => '查看货物',
                'group' => 'my_applications',
                'description' => 'Кнопка просмотра грузов'
            ],
            [
                'key' => 'my_applications.stats_pending',
                'rus' => 'Ожидают',
                'kaz' => 'Күтуде',
                'chn' => '等待中',
                'group' => 'my_applications',
                'description' => 'Статистика: ожидающие заявки'
            ],
            [
                'key' => 'my_applications.stats_approved',
                'rus' => 'Подтверждены',
                'kaz' => 'Бекітілген',
                'chn' => '已确认',
                'group' => 'my_applications',
                'description' => 'Статистика: подтвержденные заявки'
            ],
            [
                'key' => 'my_applications.stats_rejected',
                'rus' => 'Отклонены',
                'kaz' => 'Бас тартылған',
                'chn' => '已拒绝',
                'group' => 'my_applications',
                'description' => 'Статистика: отклоненные заявки'
            ],
            [
                'key' => 'my_applications.pending_title',
                'rus' => 'Ожидающие заявки',
                'kaz' => 'Күтудегі өтініштер',
                'chn' => '等待中的申请',
                'group' => 'my_applications',
                'description' => 'Заголовок секции ожидающих заявок'
            ],
            [
                'key' => 'my_applications.approved_title',
                'rus' => 'Подтвержденные заявки',
                'kaz' => 'Бекітілген өтініштер',
                'chn' => '已确认的申请',
                'group' => 'my_applications',
                'description' => 'Заголовок секции подтвержденных заявок'
            ],
            [
                'key' => 'my_applications.rejected_title',
                'rus' => 'Отклоненные заявки',
                'kaz' => 'Бас тартылған өтініштер',
                'chn' => '已拒绝的申请',
                'group' => 'my_applications',
                'description' => 'Заголовок секции отклоненных заявок'
            ],
            [
                'key' => 'my_applications.table_route',
                'rus' => 'Маршрут',
                'kaz' => 'Маршрут',
                'chn' => '路线',
                'group' => 'my_applications',
                'description' => 'Заголовок колонки маршрута'
            ],
            [
                'key' => 'my_applications.table_cargo',
                'rus' => 'Груз',
                'kaz' => 'Жүк',
                'chn' => '货物',
                'group' => 'my_applications',
                'description' => 'Заголовок колонки груза'
            ],
            [
                'key' => 'my_applications.table_submitted',
                'rus' => 'Подана',
                'kaz' => 'Берілген',
                'chn' => '提交时间',
                'group' => 'my_applications',
                'description' => 'Заголовок колонки подачи'
            ],
            [
                'key' => 'my_applications.table_actions',
                'rus' => 'Действия',
                'kaz' => 'Әрекеттер',
                'chn' => '操作',
                'group' => 'my_applications',
                'description' => 'Заголовок колонки действий'
            ],
            [
                'key' => 'my_applications.status_pending',
                'rus' => 'Ожидает',
                'kaz' => 'Күтуде',
                'chn' => '等待中',
                'group' => 'my_applications',
                'description' => 'Статус ожидающей заявки'
            ],
            [
                'key' => 'my_applications.status_approved',
                'rus' => 'Подтверждено',
                'kaz' => 'Бекітілген',
                'chn' => '已确认',
                'group' => 'my_applications',
                'description' => 'Статус подтвержденной заявки'
            ],
            [
                'key' => 'my_applications.status_rejected',
                'rus' => 'Отклонено',
                'kaz' => 'Бас тартылған',
                'chn' => '已拒绝',
                'group' => 'my_applications',
                'description' => 'Статус отклоненной заявки'
            ],
            [
                'key' => 'my_applications.view_details',
                'rus' => 'Подробнее',
                'kaz' => 'Толығырақ',
                'chn' => '详情',
                'group' => 'my_applications',
                'description' => 'Кнопка просмотра деталей'
            ],
            [
                'key' => 'my_applications.volume_weight',
                'rus' => 'м³, кг',
                'kaz' => 'м³, кг',
                'chn' => '立方米, 公斤',
                'group' => 'my_applications',
                'description' => 'Единицы измерения объема и веса'
            ],
            [
                'key' => 'my_applications.volume_label',
                'rus' => 'Объем:',
                'kaz' => 'Көлемі:',
                'chn' => '体积：',
                'group' => 'my_applications',
                'description' => 'Метка объема в мобильной карточке'
            ],
            [
                'key' => 'my_applications.weight_label',
                'rus' => 'Вес:',
                'kaz' => 'Салмағы:',
                'chn' => '重量：',
                'group' => 'my_applications',
                'description' => 'Метка веса в мобильной карточке'
            ],
            [
                'key' => 'my_applications.submitted_label',
                'rus' => 'Подана:',
                'kaz' => 'Берілген:',
                'chn' => '提交时间：',
                'group' => 'my_applications',
                'description' => 'Метка времени подачи'
            ],
            [
                'key' => 'my_applications.driver_notes_label',
                'rus' => 'Ваши заметки:',
                'kaz' => 'Сіздің ескертпелеріңіз:',
                'chn' => '您的备注：',
                'group' => 'my_applications',
                'description' => 'Метка заметок водителя'
            ],
            [
                'key' => 'my_applications.no_applications_title',
                'rus' => 'У вас пока нет заявок',
                'kaz' => 'Сізде әзірше өтініштер жоқ',
                'chn' => '您目前没有申请',
                'group' => 'my_applications',
                'description' => 'Заголовок при отсутствии заявок'
            ],
            [
                'key' => 'my_applications.no_applications_desc',
                'rus' => 'Подайте заявку на любой доступный груз, чтобы начать работу',
                'kaz' => 'Жұмысты бастау үшін кез келген қолжетімді жүкке өтініш беріңіз',
                'chn' => '申请任何可用的货物开始工作',
                'group' => 'my_applications',
                'description' => 'Описание при отсутствии заявок'
            ],
            [
                'key' => 'my_applications.view_available_cargo',
                'rus' => 'Посмотреть доступные грузы',
                'kaz' => 'Қолжетімді жүктерді көру',
                'chn' => '查看可用货物',
                'group' => 'my_applications',
                'description' => 'Кнопка просмотра доступных грузов'
            ],
            [
                'key' => 'my_applications.back_button',
                'rus' => 'Назад',
                'kaz' => 'Артқа',
                'chn' => '返回',
                'group' => 'my_applications',
                'description' => 'Кнопка возврата'
            ],
            [
                'key' => 'admin.delete_user',
                'rus' => 'Удалить',
                'kaz' => 'Жою',
                'chn' => '删除',
                'group' => 'admin',
                'description' => 'Кнопка удаления пользователя'
            ],
            [
                'key' => 'admin.confirm_delete',
                'rus' => 'Удалить этого пользователя?',
                'kaz' => 'Бұл пайдаланушыны жою керек пе?',
                'chn' => '删除此用户？',
                'group' => 'admin',
                'description' => 'Подтверждение удаления пользователя'
            ],
            [
                'key' => 'admin.confirm_reject',
                'rus' => 'Отклонить этого пользователя?',
                'kaz' => 'Бұл пайдаланушыны бас тарту керек пе?',
                'chn' => '拒绝此用户？',
                'group' => 'admin',
                'description' => 'Подтверждение отклонения пользователя'
            ],
            [
                'key' => 'admin.warehouse_employee',
                'rus' => 'Сотрудник склада',
                'kaz' => 'Қойма қызметкері',
                'chn' => '仓库员工',
                'group' => 'admin',
                'description' => 'Роль сотрудника склада'
            ],
            [
                'key' => 'admin.driver',
                'rus' => 'Водитель',
                'kaz' => 'Жүргізуші',
                'chn' => '司机',
                'group' => 'admin',
                'description' => 'Роль водителя'
            ],
            [
                'key' => 'admin.administrator',
                'rus' => 'Администратор',
                'kaz' => 'Әкімші',
                'chn' => '管理员',
                'group' => 'admin',
                'description' => 'Роль администратора'
            ],
            [
                'key' => 'admin.registered',
                'rus' => 'Зарегистрирован:',
                'kaz' => 'Тіркелген:',
                'chn' => '已注册：',
                'group' => 'admin',
                'description' => 'Дата регистрации'
            ],
            [
                'key' => 'admin.approved',
                'rus' => 'Подтвержден:',
                'kaz' => 'Бекітілген:',
                'chn' => '已确认：',
                'group' => 'admin',
                'description' => 'Дата подтверждения'
            ],
            
            // Страницы заявок
            [
                'key' => 'applications.title',
                'rus' => 'Заявки на грузы',
                'kaz' => 'Жүктерге өтініштер',
                'chn' => '货物申请',
                'group' => 'applications',
                'description' => 'Заголовок страницы заявок'
            ],
            [
                'key' => 'applications.all_applications',
                'rus' => 'Все заявки в системе',
                'kaz' => 'Жүйедегі барлық өтініштер',
                'chn' => '系统中的所有申请',
                'group' => 'applications',
                'description' => 'Описание для администраторов'
            ],
            [
                'key' => 'applications.your_cargo_applications',
                'rus' => 'Заявки на ваши грузы',
                'kaz' => 'Сіздің жүктеріңізге өтініштер',
                'chn' => '您货物的申请',
                'group' => 'applications',
                'description' => 'Описание для владельцев грузов'
            ],
            [
                'key' => 'applications.status_label',
                'rus' => 'Статус',
                'kaz' => 'Күй',
                'chn' => '状态',
                'group' => 'applications',
                'description' => 'Метка поля статуса'
            ],
            [
                'key' => 'applications.search_label',
                'rus' => 'Поиск',
                'kaz' => 'Іздеу',
                'chn' => '搜索',
                'group' => 'applications',
                'description' => 'Метка поля поиска'
            ],
            [
                'key' => 'applications.search_placeholder',
                'rus' => 'Поиск по маршруту или водителю',
                'kaz' => 'Маршрут немесе жүргізуші бойынша іздеу',
                'chn' => '按路线或司机搜索',
                'group' => 'applications',
                'description' => 'Плейсхолдер поиска заявок'
            ],
            [
                'key' => 'applications.search_button',
                'rus' => 'Поиск',
                'kaz' => 'Іздеу',
                'chn' => '搜索',
                'group' => 'applications',
                'description' => 'Кнопка поиска'
            ],
            [
                'key' => 'applications.table_route',
                'rus' => 'Маршрут',
                'kaz' => 'Маршрут',
                'chn' => '路线',
                'group' => 'applications',
                'description' => 'Заголовок колонки маршрута'
            ],
            [
                'key' => 'applications.table_driver',
                'rus' => 'Водитель',
                'kaz' => 'Жүргізуші',
                'chn' => '司机',
                'group' => 'applications',
                'description' => 'Заголовок колонки водителя'
            ],
            [
                'key' => 'applications.table_status',
                'rus' => 'Статус',
                'kaz' => 'Күй',
                'chn' => '状态',
                'group' => 'applications',
                'description' => 'Заголовок колонки статуса'
            ],
            [
                'key' => 'applications.table_submitted',
                'rus' => 'Подана',
                'kaz' => 'Ұсынылды',
                'chn' => '已提交',
                'group' => 'applications',
                'description' => 'Заголовок колонки подачи'
            ],
            [
                'key' => 'applications.table_actions',
                'rus' => 'Действия',
                'kaz' => 'Әрекеттер',
                'chn' => '操作',
                'group' => 'applications',
                'description' => 'Заголовок колонки действий'
            ],
            [
                'key' => 'applications.status_pending',
                'rus' => 'Ожидает',
                'kaz' => 'Күтуде',
                'chn' => '等待中',
                'group' => 'applications',
                'description' => 'Статус ожидания заявки'
            ],
            [
                'key' => 'applications.status_approved',
                'rus' => 'Подтверждена',
                'kaz' => 'Бекітілді',
                'chn' => '已确认',
                'group' => 'applications',
                'description' => 'Статус подтвержденной заявки'
            ],
            [
                'key' => 'applications.status_rejected',
                'rus' => 'Отклонена',
                'kaz' => 'Қабылданбады',
                'chn' => '已拒绝',
                'group' => 'applications',
                'description' => 'Статус отклоненной заявки'
            ],
            [
                'key' => 'applications.no_applications',
                'rus' => 'Заявки не найдены',
                'kaz' => 'Өтініштер табылмады',
                'chn' => '未找到申请',
                'group' => 'applications',
                'description' => 'Сообщение об отсутствии заявок'
            ],
            [
                'key' => 'applications.no_applications_desc',
                'rus' => 'В данный момент нет заявок на перевозку',
                'kaz' => 'Қазіргі уақытта тасымалдауға өтініштер жоқ',
                'chn' => '目前没有运输申请',
                'group' => 'applications',
                'description' => 'Описание отсутствия заявок'
            ],
            
            // Формы создания и редактирования грузов
            [
                'key' => 'cargo.create_title',
                'rus' => 'Добавить груз - Silk Way',
                'kaz' => 'Жүк қосу - Silk Way',
                'chn' => '添加货物 - Silk Way',
                'group' => 'cargo',
                'description' => 'Заголовок страницы создания груза'
            ],
            [
                'key' => 'cargo.new_cargo',
                'rus' => 'Новый груз',
                'kaz' => 'Жаңа жүк',
                'chn' => '新货物',
                'group' => 'cargo',
                'description' => 'Заголовок формы нового груза'
            ],
            [
                'key' => 'cargo.create_desc',
                'rus' => 'Заполните информацию о грузе для отправки',
                'kaz' => 'Жіберуге арналған жүк туралы ақпаратты толтырыңыз',
                'chn' => '填写要发送的货物信息',
                'group' => 'cargo',
                'description' => 'Описание формы создания груза'
            ],
            [
                'key' => 'cargo.from_location',
                'rus' => 'Откуда',
                'kaz' => 'Қайдан',
                'chn' => '从哪里',
                'group' => 'cargo',
                'description' => 'Поле места отправления'
            ],
            [
                'key' => 'cargo.to_location',
                'rus' => 'Куда',
                'kaz' => 'Қайда',
                'chn' => '到哪里',
                'group' => 'cargo',
                'description' => 'Поле места назначения'
            ],
            [
                'key' => 'cargo.cargo_type',
                'rus' => 'Тип груза',
                'kaz' => 'Жүк түрі',
                'chn' => '货物类型',
                'group' => 'cargo',
                'description' => 'Поле типа груза'
            ],
            [
                'key' => 'cargo.volume',
                'rus' => 'Объем (м³)',
                'kaz' => 'Көлем (м³)',
                'chn' => '体积 (m³)',
                'group' => 'cargo',
                'description' => 'Поле объема груза'
            ],
            [
                'key' => 'cargo.weight',
                'rus' => 'Вес (кг)',
                'kaz' => 'Салмақ (кг)',
                'chn' => '重量 (kg)',
                'group' => 'cargo',
                'description' => 'Поле веса груза'
            ],
            [
                'key' => 'cargo.ready_date',
                'rus' => 'Дата и время готовности',
                'kaz' => 'Дайындық күні мен уақыты',
                'chn' => '准备就绪的日期和时间',
                'group' => 'cargo',
                'description' => 'Поле даты готовности'
            ],
            [
                'key' => 'cargo.comment',
                'rus' => 'Комментарий / контакт',
                'kaz' => 'Түсініктеме / байланыс',
                'chn' => '评论/联系',
                'group' => 'cargo',
                'description' => 'Поле комментария'
            ],
            [
                'key' => 'cargo.comment_placeholder',
                'rus' => 'Дополнительная информация, контактные данные...',
                'kaz' => 'Қосымша ақпарат, байланыс деректері...',
                'chn' => '附加信息、联系信息...',
                'group' => 'cargo',
                'description' => 'Плейсхолдер комментария'
            ],
            [
                'key' => 'cargo.cancel',
                'rus' => 'Отмена',
                'kaz' => 'Бас тарту',
                'chn' => '取消',
                'group' => 'cargo',
                'description' => 'Кнопка отмены'
            ],
            [
                'key' => 'cargo.create_cargo',
                'rus' => 'Создать груз',
                'kaz' => 'Жүк құру',
                'chn' => '创建货物',
                'group' => 'cargo',
                'description' => 'Кнопка создания груза'
            ],
            [
                'key' => 'cargo.edit_title',
                'rus' => 'Редактировать груз - Silk Way',
                'kaz' => 'Жүкті өңдеу - Silk Way',
                'chn' => '编辑货物 - Silk Way',
                'group' => 'cargo',
                'description' => 'Заголовок страницы редактирования груза'
            ],
            [
                'key' => 'cargo.edit_cargo',
                'rus' => 'Редактировать груз',
                'kaz' => 'Жүкті өңдеу',
                'chn' => '编辑货物',
                'group' => 'cargo',
                'description' => 'Кнопка редактирования груза'
            ],
            [
                'key' => 'cargo.update_cargo',
                'rus' => 'Обновить груз',
                'kaz' => 'Жүкті жаңарту',
                'chn' => '更新货物',
                'group' => 'cargo',
                'description' => 'Кнопка обновления груза'
            ],
            
            // Формы создания и редактирования машин
            [
                'key' => 'cars.create_title',
                'rus' => 'Добавить машину - Silk Way',
                'kaz' => 'Машина қосу - Silk Way',
                'chn' => '添加车辆 - Silk Way',
                'group' => 'car',
                'description' => 'Заголовок страницы создания машины'
            ],
            [
                'key' => 'cars.new_car',
                'rus' => 'Новая машина',
                'kaz' => 'Жаңа машина',
                'chn' => '新车辆',
                'group' => 'car',
                'description' => 'Заголовок формы новой машины'
            ],
            [
                'key' => 'cars.create_desc',
                'rus' => 'Заполните информацию о машине и прицепе',
                'kaz' => 'Машина мен тіркеме туралы ақпаратты толтырыңыз',
                'chn' => '填写车辆和拖车信息',
                'group' => 'car',
                'description' => 'Описание формы создания машины'
            ],
            [
                'key' => 'cars.brand',
                'rus' => 'Марка',
                'kaz' => 'Марка',
                'chn' => '品牌',
                'group' => 'car',
                'description' => 'Поле марки автомобиля'
            ],
            [
                'key' => 'cars.model',
                'rus' => 'Модель',
                'kaz' => 'Модель',
                'chn' => '型号',
                'group' => 'car',
                'description' => 'Поле модели автомобиля'
            ],
            [
                'key' => 'cars.license_plate',
                'rus' => 'Гос. номер',
                'kaz' => 'Мемлекеттік нөмір',
                'chn' => '车牌号',
                'group' => 'car',
                'description' => 'Поле государственного номера'
            ],
            [
                'key' => 'cars.max_weight',
                'rus' => 'Макс. вес (т)',
                'kaz' => 'Макс. салмақ (т)',
                'chn' => '最大重量 (t)',
                'group' => 'car',
                'description' => 'Поле максимального веса'
            ],
            [
                'key' => 'cars.trailer_type',
                'rus' => 'Тип прицепа',
                'kaz' => 'Тіркеме түрі',
                'chn' => '拖车类型',
                'group' => 'car',
                'description' => 'Поле типа прицепа'
            ],
            [
                'key' => 'cars.trailer_length',
                'rus' => 'Длина прицепа (м)',
                'kaz' => 'Тіркеме ұзындығы (м)',
                'chn' => '拖车长度 (m)',
                'group' => 'car',
                'description' => 'Поле длины прицепа'
            ],
            [
                'key' => 'cars.trailer_width',
                'rus' => 'Ширина прицепа (м)',
                'kaz' => 'Тіркеме ені (м)',
                'chn' => '拖车宽度 (m)',
                'group' => 'car',
                'description' => 'Поле ширины прицепа'
            ],
            [
                'key' => 'cars.trailer_height',
                'rus' => 'Высота прицепа (м)',
                'kaz' => 'Тіркеме биіктігі (м)',
                'chn' => '拖车高度 (m)',
                'group' => 'car',
                'description' => 'Поле высоты прицепа'
            ],
            [
                'key' => 'cars.vehicle_document',
                'rus' => 'Документ ПДД (PDF)',
                'kaz' => 'Жол қауіпсіздігі қағидалары құжаты (PDF)',
                'chn' => '交通规则文件 (PDF)',
                'group' => 'car',
                'description' => 'Поле документа ПДД'
            ],
            [
                'key' => 'cars.create_car',
                'rus' => 'Создать машину',
                'kaz' => 'Машина құру',
                'chn' => '创建车辆',
                'group' => 'car',
                'description' => 'Кнопка создания машины'
            ],
            [
                'key' => 'cars.edit_title',
                'rus' => 'Редактировать машину - Silk Way',
                'kaz' => 'Машинаны өңдеу - Silk Way',
                'chn' => '编辑车辆 - Silk Way',
                'group' => 'car',
                'description' => 'Заголовок страницы редактирования машины'
            ],
            [
                'key' => 'cars.update_car',
                'rus' => 'Обновить машину',
                'kaz' => 'Машинаны жаңарту',
                'chn' => '更新车辆',
                'group' => 'car',
                'description' => 'Кнопка обновления машины'
            ],
            
            // Общие элементы и сообщения
            [
                'key' => 'common.success',
                'rus' => 'Успешно!',
                'kaz' => 'Сәтті!',
                'chn' => '成功！',
                'group' => 'common',
                'description' => 'Сообщение об успехе'
            ],
            [
                'key' => 'common.error',
                'rus' => 'Ошибка!',
                'kaz' => 'Қате!',
                'chn' => '错误！',
                'group' => 'common',
                'description' => 'Сообщение об ошибке'
            ],
            [
                'key' => 'common.warning',
                'rus' => 'Внимание!',
                'kaz' => 'Назар аударыңыз!',
                'chn' => '注意！',
                'group' => 'common',
                'description' => 'Предупреждение'
            ],
            [
                'key' => 'common.info',
                'rus' => 'Информация',
                'kaz' => 'Ақпарат',
                'chn' => '信息',
                'group' => 'common',
                'description' => 'Информационное сообщение'
            ],
            [
                'key' => 'common.confirm',
                'rus' => 'Подтверждение',
                'kaz' => 'Растау',
                'chn' => '确认',
                'group' => 'common',
                'description' => 'Заголовок подтверждения'
            ],
            [
                'key' => 'common.yes',
                'rus' => 'Да',
                'kaz' => 'Иә',
                'chn' => '是',
                'group' => 'common',
                'description' => 'Кнопка подтверждения'
            ],
            [
                'key' => 'common.no',
                'rus' => 'Нет',
                'kaz' => 'Жоқ',
                'chn' => '否',
                'group' => 'common',
                'description' => 'Кнопка отказа'
            ],
            [
                'key' => 'common.loading',
                'rus' => 'Загрузка...',
                'kaz' => 'Жүктелуде...',
                'chn' => '加载中...',
                'group' => 'common',
                'description' => 'Сообщение о загрузке'
            ],
            [
                'key' => 'common.no_data',
                'rus' => 'Нет данных',
                'kaz' => 'Деректер жоқ',
                'chn' => '无数据',
                'group' => 'common',
                'description' => 'Сообщение об отсутствии данных'
            ],
            [
                'key' => 'common.actions',
                'rus' => 'Действия',
                'kaz' => 'Әрекеттер',
                'chn' => '操作',
                'group' => 'common',
                'description' => 'Заголовок колонки действий'
            ],
            [
                'key' => 'common.view',
                'rus' => 'Просмотр',
                'kaz' => 'Көру',
                'chn' => '查看',
                'group' => 'common',
                'description' => 'Кнопка просмотра'
            ],
            [
                'key' => 'common.edit',
                'rus' => 'Редактировать',
                'kaz' => 'Өңдеу',
                'chn' => '编辑',
                'group' => 'common',
                'description' => 'Кнопка редактирования'
            ],
            [
                'key' => 'common.delete',
                'rus' => 'Удалить',
                'kaz' => 'Жою',
                'chn' => '删除',
                'group' => 'common',
                'description' => 'Кнопка удаления'
            ],
            [
                'key' => 'common.back',
                'rus' => 'Назад',
                'kaz' => 'Артқа',
                'chn' => '返回',
                'group' => 'common',
                'description' => 'Кнопка назад'
            ],
            [
                'key' => 'common.close',
                'rus' => 'Закрыть',
                'kaz' => 'Жабу',
                'chn' => '关闭',
                'group' => 'common',
                'description' => 'Кнопка закрытия'
            ],
            [
                'key' => 'brand',
                'rus' => 'Марка',
                'kaz' => 'Марка',
                'chn' => '品牌',
                'group' => 'car',
                'description' => 'Поле марки автомобиля'
            ],
            [
                'key' => 'model',
                'rus' => 'Модель',
                'kaz' => 'Модель',
                'chn' => '型号',
                'group' => 'car',
                'description' => 'Поле модели автомобиля'
            ],
            [
                'key' => 'license_plate',
                'rus' => 'Гос. номер',
                'kaz' => 'Мемлекеттік нөмір',
                'chn' => '车牌号',
                'group' => 'car',
                'description' => 'Поле государственного номера'
            ],
            [
                'key' => 'max_weight',
                'rus' => 'Макс. вес',
                'kaz' => 'Макс. салмақ',
                'chn' => '最大重量',
                'group' => 'car',
                'description' => 'Поле максимального веса'
            ],
            [
                'key' => 'trailer_type',
                'rus' => 'Тип прицепа',
                'kaz' => 'Тіркеме түрі',
                'chn' => '拖车类型',
                'group' => 'car',
                'description' => 'Поле типа прицепа'
            ],
            [
                'key' => 'trailer_length',
                'rus' => 'Длина прицепа',
                'kaz' => 'Тіркеме ұзындығы',
                'chn' => '拖车长度',
                'group' => 'car',
                'description' => 'Поле длины прицепа'
            ],
            [
                'key' => 'trailer_width',
                'rus' => 'Ширина прицепа',
                'kaz' => 'Тіркеме ені',
                'chn' => '拖车宽度',
                'group' => 'car',
                'description' => 'Поле ширины прицепа'
            ],
            [
                'key' => 'trailer_height',
                'rus' => 'Высота прицепа',
                'kaz' => 'Тіркеме биіктігі',
                'chn' => '拖车高度',
                'group' => 'car',
                'description' => 'Поле высоты прицепа'
            ],
            [
                'key' => 'trailer_volume',
                'rus' => 'Объем прицепа',
                'kaz' => 'Тіркеме көлемі',
                'chn' => '拖车体积',
                'group' => 'car',
                'description' => 'Поле объема прицепа'
            ],
            
            // Переводы для пользователей
            [
                'key' => 'user',
                'rus' => 'Пользователь',
                'kaz' => 'Пайдаланушы',
                'chn' => '用户',
                'group' => 'user',
                'description' => 'Название пользователя'
            ],
            [
                'key' => 'name',
                'rus' => 'Имя',
                'kaz' => 'Аты',
                'chn' => '姓名',
                'group' => 'user',
                'description' => 'Поле имени'
            ],
            [
                'key' => 'role',
                'rus' => 'Роль',
                'kaz' => 'Рөл',
                'chn' => '角色',
                'group' => 'user',
                'description' => 'Поле роли'
            ],
            [
                'key' => 'admin',
                'rus' => 'Администратор',
                'kaz' => 'Әкімші',
                'chn' => '管理员',
                'group' => 'user',
                'description' => 'Роль администратора'
            ],
            [
                'key' => 'driver',
                'rus' => 'Водитель',
                'kaz' => 'Жүргізуші',
                'chn' => '司机',
                'group' => 'user',
                'description' => 'Роль водителя'
            ],
            [
                'key' => 'warehouse_employee',
                'rus' => 'Сотрудник склада',
                'kaz' => 'Қойма қызметкері',
                'chn' => '仓库员工',
                'group' => 'user',
                'description' => 'Роль сотрудника склада'
            ],
            [
                'key' => 'approved',
                'rus' => 'Одобрен',
                'kaz' => 'Бекітілді',
                'chn' => '已批准',
                'group' => 'user',
                'description' => 'Статус одобрения'
            ],
            [
                'key' => 'pending_approval',
                'rus' => 'Ожидает одобрения',
                'kaz' => 'Бекіту күтуде',
                'chn' => '等待批准',
                'group' => 'user',
                'description' => 'Статус ожидания одобрения'
            ],
            
            // Переводы для заявок
            [
                'key' => 'application',
                'rus' => 'Заявка',
                'kaz' => 'Өтініш',
                'chn' => '申请',
                'group' => 'application',
                'description' => 'Название заявки'
            ],
            [
                'key' => 'pending',
                'rus' => 'Ожидает',
                'kaz' => 'Күтуде',
                'chn' => '等待中',
                'group' => 'application',
                'description' => 'Статус ожидания'
            ],
            [
                'key' => 'approved',
                'rus' => 'Одобрено',
                'kaz' => 'Бекітілді',
                'chn' => '已批准',
                'group' => 'application',
                'description' => 'Статус одобрения'
            ],
            [
                'key' => 'rejected',
                'rus' => 'Отклонено',
                'kaz' => 'Қабылданбады',
                'chn' => '已拒绝',
                'group' => 'application',
                'description' => 'Статус отклонения'
            ],
            
            // Переводы для админки
            [
                'key' => 'dashboard',
                'rus' => 'Панель управления',
                'kaz' => 'Басқару панелі',
                'chn' => '控制面板',
                'group' => 'admin',
                'description' => 'Название панели управления'
            ],
            [
                'key' => 'translations',
                'rus' => 'Переводы',
                'kaz' => 'Аудармалар',
                'chn' => '翻译',
                'group' => 'admin',
                'description' => 'Название раздела переводов'
            ],
            [
                'key' => 'manage_translations',
                'rus' => 'Управление переводами',
                'kaz' => 'Аудармаларды басқару',
                'chn' => '管理翻译',
                'group' => 'admin',
                'description' => 'Название страницы управления переводами'
            ],
            [
                'key' => 'add_translation',
                'rus' => 'Добавить перевод',
                'kaz' => 'Аударма қосу',
                'chn' => '添加翻译',
                'group' => 'admin',
                'description' => 'Кнопка добавления перевода'
            ],
            [
                'key' => 'edit_translation',
                'rus' => 'Редактировать перевод',
                'kaz' => 'Аударманы өңдеу',
                'chn' => '编辑翻译',
                'group' => 'admin',
                'description' => 'Кнопка редактирования перевода'
            ],
            [
                'key' => 'translation_key',
                'rus' => 'Ключ перевода',
                'kaz' => 'Аударма кілті',
                'chn' => '翻译键',
                'group' => 'admin',
                'description' => 'Поле ключа перевода'
            ],
            [
                'key' => 'translation_group',
                'rus' => 'Группа',
                'kaz' => 'Топ',
                'chn' => '组',
                'group' => 'admin',
                'description' => 'Поле группы переводов'
            ],
            [
                'key' => 'translation_description',
                'rus' => 'Описание',
                'kaz' => 'Сипаттама',
                'chn' => '描述',
                'group' => 'admin',
                'description' => 'Поле описания перевода'
            ],
            [
                'key' => 'russian',
                'rus' => 'Русский',
                'kaz' => 'Орысша',
                'chn' => '俄语',
                'group' => 'admin',
                'description' => 'Название русского языка'
            ],
            [
                'key' => 'kazakh',
                'rus' => 'Казахский',
                'kaz' => 'Қазақша',
                'chn' => '哈萨克语',
                'group' => 'admin',
                'description' => 'Название казахского языка'
            ],
            [
                'key' => 'chinese',
                'rus' => 'Китайский',
                'kaz' => 'Қытайша',
                'chn' => '中文',
                'group' => 'admin',
                'description' => 'Название китайского языка'
            ],

            // Переводы для хедера и навигации
            [
                'key' => 'header.admin_panel',
                'rus' => 'Админ-панель',
                'kaz' => 'Админ панель',
                'chn' => '管理面板',
                'group' => 'header',
                'description' => 'Ссылка на админ-панель в хедере'
            ],
            [
                'key' => 'header.users',
                'rus' => 'Пользователи',
                'kaz' => 'Пайдаланушылар',
                'chn' => '用户',
                'group' => 'header',
                'description' => 'Ссылка на пользователей в хедере'
            ],
            [
                'key' => 'header.cargo',
                'rus' => 'Грузы',
                'kaz' => 'Жүктер',
                'chn' => '货物',
                'group' => 'header',
                'description' => 'Ссылка на грузы в хедере'
            ],
            [
                'key' => 'header.add_cargo',
                'rus' => 'Добавить груз',
                'kaz' => 'Жүк қосу',
                'chn' => '添加货物',
                'group' => 'header',
                'description' => 'Ссылка на добавление груза в хедере'
            ],
            [
                'key' => 'header.applications',
                'rus' => 'Заявки',
                'kaz' => 'Өтініштер',
                'chn' => '申请',
                'group' => 'header',
                'description' => 'Ссылка на заявки в хедере'
            ],
            [
                'key' => 'header.all_cars',
                'rus' => 'Все машины',
                'kaz' => 'Барлық көліктер',
                'chn' => '所有车辆',
                'group' => 'header',
                'description' => 'Ссылка на все машины в хедере'
            ],
            [
                'key' => 'header.my_cargo',
                'rus' => 'Мои грузы',
                'kaz' => 'Менің жүктерім',
                'chn' => '我的货物',
                'group' => 'header',
                'description' => 'Ссылка на мои грузы в хедере'
            ],
            [
                'key' => 'header.my_applications',
                'rus' => 'Мои заявки',
                'kaz' => 'Менің өтініштерім',
                'chn' => '我的申请',
                'group' => 'header',
                'description' => 'Ссылка на мои заявки в хедере'
            ],
            [
                'key' => 'header.my_cars',
                'rus' => 'Мои машины',
                'kaz' => 'Менің көліктерім',
                'chn' => '我的车辆',
                'group' => 'header',
                'description' => 'Ссылка на мои машины в хедере'
            ],
            [
                'key' => 'header.logout',
                'rus' => 'Выйти',
                'kaz' => 'Шығу',
                'chn' => '退出',
                'group' => 'header',
                'description' => 'Кнопка выхода в хедере'
            ],
            [
                'key' => 'header.role_admin',
                'rus' => 'Админ',
                'kaz' => 'Админ',
                'chn' => '管理员',
                'group' => 'header',
                'description' => 'Роль администратора в хедере'
            ],
            [
                'key' => 'header.role_warehouse',
                'rus' => 'Склад',
                'kaz' => 'Қойма',
                'chn' => '仓库',
                'group' => 'header',
                'description' => 'Роль складского работника в хедере'
            ],
            [
                'key' => 'header.role_driver',
                'rus' => 'Водитель',
                'kaz' => 'Жүргізуші',
                'chn' => '司机',
                'group' => 'header',
                'description' => 'Роль водителя в хедере'
            ],
            [
                'key' => 'header.profile',
                'rus' => 'Профиль',
                'kaz' => 'Профиль',
                'chn' => '个人资料',
                'group' => 'header',
                'description' => 'Ссылка на профиль в мобильной навигации'
            ],
            [
                'key' => 'header.cars',
                'rus' => 'Машины',
                'kaz' => 'Көліктер',
                'chn' => '车辆',
                'group' => 'header',
                'description' => 'Ссылка на машины в мобильной навигации'
            ],
            [
                'key' => 'header.footer_text',
                'rus' => 'Система управления грузоперевозками.',
                'kaz' => 'Жүк тасымалдау басқару жүйесі.',
                'chn' => '货运管理系统。',
                'group' => 'header',
                'description' => 'Текст в футере'
            ],
            
            // Дополнительные переводы для аутентификации
            [
                'key' => 'auth.email',
                'rus' => 'Email',
                'kaz' => 'Email',
                'chn' => '邮箱',
                'group' => 'auth',
                'description' => 'Поле email в формах'
            ],
            [
                'key' => 'auth.password',
                'rus' => 'Пароль',
                'kaz' => 'Құпия сөз',
                'chn' => '密码',
                'group' => 'auth',
                'description' => 'Поле пароля в формах'
            ],
            [
                'key' => 'auth.full_name_placeholder',
                'rus' => 'Введите ваше полное имя',
                'kaz' => 'Толық атыңызды енгізіңіз',
                'chn' => '请输入您的全名',
                'group' => 'auth',
                'description' => 'Плейсхолдер для поля полного имени'
            ],
            [
                'key' => 'auth.password_confirmation',
                'rus' => 'Подтверждение пароля',
                'kaz' => 'Құпия сөзді растау',
                'chn' => '确认密码',
                'group' => 'auth',
                'description' => 'Поле подтверждения пароля'
            ],
            [
                'key' => 'auth.password_confirmation_placeholder',
                'rus' => 'Подтвердите пароль',
                'kaz' => 'Құпия сөзді растаңыз',
                'chn' => '请确认密码',
                'group' => 'auth',
                'description' => 'Плейсхолдер для подтверждения пароля'
            ],
            [
                'key' => 'auth.select_role',
                'rus' => 'Выберите роль',
                'kaz' => 'Рөлді таңдаңыз',
                'chn' => '选择角色',
                'group' => 'auth',
                'description' => 'Плейсхолдер для выбора роли'
            ],
            
            // Дополнительные переводы для админ-панели
            [
                'key' => 'admin.add_translation',
                'rus' => 'Добавить перевод',
                'kaz' => 'Аударма қосу',
                'chn' => '添加翻译',
                'group' => 'admin',
                'description' => 'Кнопка добавления перевода'
            ],
            [
                'key' => 'admin.export',
                'rus' => 'Экспорт',
                'kaz' => 'Экспорт',
                'chn' => '导出',
                'group' => 'admin',
                'description' => 'Кнопка экспорта'
            ],
            [
                'key' => 'admin.clear_cache',
                'rus' => 'Очистить кэш',
                'kaz' => 'Кэшті тазалау',
                'chn' => '清除缓存',
                'group' => 'admin',
                'description' => 'Кнопка очистки кэша'
            ],
            [
                'key' => 'admin.search_by_key',
                'rus' => 'Поиск по ключу',
                'kaz' => 'Кілт бойынша іздеу',
                'chn' => '按键搜索',
                'group' => 'admin',
                'description' => 'Лейбл поиска по ключу'
            ],
            [
                'key' => 'admin.search_placeholder',
                'rus' => 'Введите ключ перевода...',
                'kaz' => 'Аударма кілтін енгізіңіз...',
                'chn' => '输入翻译键...',
                'group' => 'admin',
                'description' => 'Плейсхолдер поиска переводов'
            ],
            [
                'key' => 'admin.all_groups',
                'rus' => 'Все группы',
                'kaz' => 'Барлық топтар',
                'chn' => '所有组',
                'group' => 'admin',
                'description' => 'Опция всех групп в фильтре'
            ],
            [
                'key' => 'admin.filter',
                'rus' => 'Фильтр',
                'kaz' => 'Сүзгі',
                'chn' => '过滤',
                'group' => 'admin',
                'description' => 'Кнопка фильтрации'
            ],
            [
                'key' => 'admin.clear_filters',
                'rus' => 'Сбросить фильтры',
                'kaz' => 'Сүзгілерді тазалау',
                'chn' => '清除过滤器',
                'group' => 'admin',
                'description' => 'Кнопка сброса фильтров'
            ],
            [
                'key' => 'admin.table_key',
                'rus' => 'Ключ',
                'kaz' => 'Кілт',
                'chn' => '键',
                'group' => 'admin',
                'description' => 'Заголовок колонки ключа'
            ],
            [
                'key' => 'admin.table_russian',
                'rus' => 'Русский',
                'kaz' => 'Орысша',
                'chn' => '俄语',
                'group' => 'admin',
                'description' => 'Заголовок колонки русского языка'
            ],
            [
                'key' => 'admin.table_kazakh',
                'rus' => 'Қазақша',
                'kaz' => 'Қазақша',
                'chn' => '哈萨克语',
                'group' => 'admin',
                'description' => 'Заголовок колонки казахского языка'
            ],
            [
                'key' => 'admin.table_chinese',
                'rus' => '中文',
                'kaz' => 'Қытайша',
                'chn' => '中文',
                'group' => 'admin',
                'description' => 'Заголовок колонки китайского языка'
            ],
            [
                'key' => 'admin.table_group',
                'rus' => 'Группа',
                'kaz' => 'Топ',
                'chn' => '组',
                'group' => 'admin',
                'description' => 'Заголовок колонки группы'
            ],
            [
                'key' => 'admin.table_actions',
                'rus' => 'Действия',
                'kaz' => 'Әрекеттер',
                'chn' => '操作',
                'group' => 'admin',
                'description' => 'Заголовок колонки действий'
            ],
            [
                'key' => 'admin.no_translations_found',
                'rus' => 'Переводы не найдены',
                'kaz' => 'Аудармалар табылмады',
                'chn' => '未找到翻译',
                'group' => 'admin',
                'description' => 'Сообщение об отсутствии переводов'
            ],
            [
                'key' => 'admin.try_change_search',
                'rus' => 'Попробуйте изменить параметры поиска',
                'kaz' => 'Іздеу параметрлерін өзгертіп көріңіз',
                'chn' => '尝试更改搜索参数',
                'group' => 'admin',
                'description' => 'Подсказка по изменению поиска'
            ],
            [
                'key' => 'admin.no_translations_desc',
                'rus' => 'В данный момент нет переводов в системе',
                'kaz' => 'Қазіргі уақытта жүйеде аудармалар жоқ',
                'chn' => '目前系统中没有翻译',
                'group' => 'admin',
                'description' => 'Описание отсутствия переводов'
            ],
            [
                'key' => 'admin.reset_filters',
                'rus' => 'Сбросить фильтры',
                'kaz' => 'Сүзгілерді тазалау',
                'chn' => '重置过滤器',
                'group' => 'admin',
                'description' => 'Кнопка сброса фильтров'
            ],
            [
                'key' => 'admin.users_management_title',
                'rus' => 'Управление пользователями',
                'kaz' => 'Пайдаланушыларды басқару',
                'chn' => '用户管理',
                'group' => 'admin',
                'description' => 'Заголовок страницы управления пользователями'
            ],
            [
                'key' => 'admin.users_management_heading',
                'rus' => 'Управление пользователями',
                'kaz' => 'Пайдаланушыларды басқару',
                'chn' => '用户管理',
                'group' => 'admin',
                'description' => 'Заголовок раздела управления пользователями'
            ],
            [
                'key' => 'admin.users_management_desc',
                'rus' => 'Управление пользователями системы и их правами доступа',
                'kaz' => 'Жүйе пайдаланушыларын және олардың қол жетімділік құқықтарын басқару',
                'chn' => '管理系统用户及其访问权限',
                'group' => 'admin',
                'description' => 'Описание раздела управления пользователями'
            ],
            [
                'key' => 'admin.table_user',
                'rus' => 'Пользователь',
                'kaz' => 'Пайдаланушы',
                'chn' => '用户',
                'group' => 'admin',
                'description' => 'Заголовок колонки пользователя'
            ],
            [
                'key' => 'admin.table_role',
                'rus' => 'Роль',
                'kaz' => 'Рөл',
                'chn' => '角色',
                'group' => 'admin',
                'description' => 'Заголовок колонки роли'
            ],
            [
                'key' => 'admin.table_status',
                'rus' => 'Статус',
                'kaz' => 'Күй',
                'chn' => '状态',
                'group' => 'admin',
                'description' => 'Заголовок колонки статуса'
            ],
            [
                'key' => 'admin.table_registration_date',
                'rus' => 'Дата регистрации',
                'kaz' => 'Тіркелу күні',
                'chn' => '注册日期',
                'group' => 'admin',
                'description' => 'Заголовок колонки даты регистрации'
            ],
            [
                'key' => 'admin.administrator',
                'rus' => 'Администратор',
                'kaz' => 'Әкімші',
                'chn' => '管理员',
                'group' => 'admin',
                'description' => 'Роль администратора'
            ],
            [
                'key' => 'admin.status_approved',
                'rus' => 'Подтвержден',
                'kaz' => 'Расталды',
                'chn' => '已确认',
                'group' => 'admin',
                'description' => 'Статус подтвержденного пользователя'
            ],
            [
                'key' => 'admin.status_pending',
                'rus' => 'Ожидает',
                'kaz' => 'Күтуде',
                'chn' => '等待中',
                'group' => 'admin',
                'description' => 'Статус ожидающего пользователя'
            ],
            [
                'key' => 'admin.toggle_access_title',
                'rus' => 'Переключить доступ',
                'kaz' => 'Қол жетімділікті ауыстыру',
                'chn' => '切换访问权限',
                'group' => 'admin',
                'description' => 'Подсказка для кнопки переключения доступа'
            ],
            [
                'key' => 'admin.delete_user_title',
                'rus' => 'Удалить пользователя',
                'kaz' => 'Пайдаланушыны жою',
                'chn' => '删除用户',
                'group' => 'admin',
                'description' => 'Подсказка для кнопки удаления пользователя'
            ],
        ];

        foreach ($translations as $translation) {
            Translation::updateOrCreate(
                ['key' => $translation['key']],
                $translation
            );
        }
    }
}
