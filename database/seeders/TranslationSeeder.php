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
                'ru' => 'Добро пожаловать',
                'kz' => 'Қош келдіңіз',
                'cn' => '欢迎',

            ],
            [
                'key' => 'login',
                'ru' => 'Войти',
                'kz' => 'Кіру',
                'cn' => '登录',

            ],
            [
                'key' => 'logout',
                'ru' => 'Выйти',
                'kz' => 'Шығу',
                'cn' => '退出',

            ],
            [
                'key' => 'email',
                'ru' => 'Email',
                'kz' => 'Email',
                'cn' => '邮箱',

            ],
            [
                'key' => 'password',
                'ru' => 'Пароль',
                'kz' => 'Құпия сөз',
                'cn' => '密码',

            ],
            [
                'key' => 'save',
                'ru' => 'Сохранить',
                'kz' => 'Сақтау',
                'cn' => '保存',

            ],
            [
                'key' => 'edit',
                'ru' => 'Редактировать',
                'kz' => 'Өңдеу',
                'cn' => '编辑',

            ],
            [
                'key' => 'delete',
                'ru' => 'Удалить',
                'kz' => 'Жою',
                'cn' => '删除',

            ],
            [
                'key' => 'cancel',
                'ru' => 'Отмена',
                'kz' => 'Бас тарту',
                'cn' => '取消',

            ],
            [
                'key' => 'back',
                'ru' => 'Назад',
                'kz' => 'Артқа',
                'cn' => '返回',

            ],
            [
                'key' => 'next',
                'ru' => 'Далее',
                'kz' => 'Келесі',
                'cn' => '下一步',

            ],
            [
                'key' => 'previous',
                'ru' => 'Предыдущий',
                'kz' => 'Алдыңғы',
                'cn' => '上一个',

            ],
            [
                'key' => 'search',
                'ru' => 'Поиск',
                'kz' => 'Іздеу',
                'cn' => '搜索',

            ],
            [
                'key' => 'search_placeholder',
                'ru' => 'Поиск по марке, модели или номеру...',
                'kz' => 'Марка, модель немесе нөмір бойынша іздеу...',
                'cn' => '按品牌、型号或车牌号搜索...',

            ],
            [
                'key' => 'filter',
                'ru' => 'Фильтр',
                'kz' => 'Сүзгі',
                'cn' => '筛选',

            ],
            [
                'key' => 'clear',
                'ru' => 'Очистить',
                'kz' => 'Тазалау',
                'cn' => '清除',

            ],
            
            // Переводы для грузов
            [
                'key' => 'cargo',
                'ru' => 'Груз',
                'kz' => 'Жүк',
                'cn' => '货物',

            ],
            [
                'key' => 'cargo_type',
                'ru' => 'Тип груза',
                'kz' => 'Жүк түрі',
                'cn' => '货物类型',

            ],
            [
                'key' => 'from_location',
                'ru' => 'Откуда',
                'kz' => 'Қайдан',
                'cn' => '从哪里',

            ],
            [
                'key' => 'to_location',
                'ru' => 'Куда',
                'kz' => 'Қайда',
                'cn' => '到哪里',

            ],
            [
                'key' => 'volume',
                'ru' => 'Объем',
                'kz' => 'Көлем',
                'cn' => '体积',

            ],
            [
                'key' => 'weight',
                'ru' => 'Вес',
                'kz' => 'Салмақ',
                'cn' => '重量',

            ],
            [
                'key' => 'ready_date',
                'ru' => 'Дата готовности',
                'kz' => 'Дайындық күні',
                'cn' => '准备日期',

            ],
            [
                'key' => 'comment',
                'ru' => 'Комментарий',
                'kz' => 'Түсініктеме',
                'cn' => '评论',

            ],
            [
                'key' => 'status',
                'ru' => 'Статус',
                'kz' => 'Күй',
                'cn' => '状态',

            ],
            [
                'key' => 'available',
                'ru' => 'Доступен',
                'kz' => 'Қолжетімді',
                'cn' => '可用',

            ],
            [
                'key' => 'in_progress',
                'ru' => 'В работе',
                'kz' => 'Жұмыс істеп тұр',
                'cn' => '进行中',

            ],
            [
                'key' => 'delivered',
                'ru' => 'Доставлен',
                'kz' => 'Жеткізілді',
                'cn' => '已交付',

            ],
            
            // Переводы для машин
            [
                'key' => 'car',
                'ru' => 'Машина',
                'kz' => 'Машина',
                'cn' => '汽车',

            ],
            [
                'key' => 'cars.all_cars',
                'ru' => 'Все машины',
                'kz' => 'Барлық машиналар',
                'cn' => '所有车辆',

            ],
            [
                'key' => 'cars.all_cars_description',
                'ru' => 'Список всех зарегистрированных машин в системе',
                'kz' => 'Жүйеде тіркелген барлық машиналар тізімі',
                'cn' => '系统中所有注册车辆的列表',

            ],
            [
                'key' => 'cars.add_car',
                'ru' => 'Добавить машину',
                'kz' => 'Машина қосу',
                'cn' => '添加车辆',

            ],
            [
                'key' => 'cars.all_statuses',
                'ru' => 'Все статусы',
                'kz' => 'Барлық күйлер',
                'cn' => '所有状态',

            ],
            [
                'key' => 'cars.filter',
                'ru' => 'Фильтровать',
                'kz' => 'Сүзгі',
                'cn' => '筛选',

            ],
            [
                'key' => 'cars.table_car',
                'ru' => 'Машина',
                'kz' => 'Машина',
                'cn' => '车辆',

            ],
            [
                'key' => 'cars.table_driver',
                'ru' => 'Водитель',
                'kz' => 'Жүргізуші',
                'cn' => '司机',

            ],
            [
                'key' => 'cars.table_trailer',
                'ru' => 'Прицеп',
                'kz' => 'Тіркеме',
                'cn' => '拖车',

            ],
            [
                'key' => 'cars.table_status',
                'ru' => 'Статус',
                'kz' => 'Күй',
                'cn' => '状态',

            ],
            [
                'key' => 'cars.table_actions',
                'ru' => 'Действия',
                'kz' => 'Әрекеттер',
                'cn' => '操作',

            ],
            [
                'key' => 'cars.status_active',
                'ru' => 'Активна',
                'kz' => 'Белсенді',
                'cn' => '活跃',

            ],
            [
                'key' => 'cars.status_inactive',
                'ru' => 'Неактивна',
                'kz' => 'Белсенді емес',
                'cn' => '不活跃',

            ],
            [
                'key' => 'cars.driver',
                'ru' => 'Водитель:',
                'kz' => 'Жүргізуші:',
                'cn' => '司机:',

            ],
            [
                'key' => 'cars.email',
                'ru' => 'Email:',
                'kz' => 'Email:',
                'cn' => '邮箱:',

            ],
            [
                'key' => 'cars.trailer',
                'ru' => 'Прицеп:',
                'kz' => 'Тіркеме:',
                'cn' => '拖车:',

            ],
            [
                'key' => 'cars.dimensions',
                'ru' => 'Габариты:',
                'kz' => 'Өлшемдер:',
                'cn' => '尺寸:',

            ],
            [
                'key' => 'cars.view',
                'ru' => 'Просмотр',
                'kz' => 'Көру',
                'cn' => '查看',

            ],
            [
                'key' => 'cars.edit',
                'ru' => 'Редактировать',
                'kz' => 'Өңдеу',
                'cn' => '编辑',

            ],
            
            // Главная страница
            [
                'key' => 'welcome.title',
                'ru' => 'Silk Way - Система управления грузоперевозками',
                'kz' => 'Silk Way - Жүк тасымалдау басқару жүйесі',
                'cn' => 'Silk Way - 货运管理系统',

            ],
            [
                'key' => 'welcome.system_title',
                'ru' => 'Система управления грузоперевозками',
                'kz' => 'Жүк тасымалдау басқару жүйесі',
                'cn' => '货运管理系统',

            ],
            [
                'key' => 'welcome.cargo_management',
                'ru' => 'Управление грузами',
                'kz' => 'Жүктерді басқару',
                'cn' => '货物管理',

            ],
            [
                'key' => 'welcome.cargo_management_desc',
                'ru' => 'Создание, редактирование и отслеживание грузов',
                'kz' => 'Жүктерді құру, өңдеу және қадағалау',
                'cn' => '创建、编辑和跟踪货物',

            ],
            [
                'key' => 'welcome.create_applications',
                'ru' => 'Создание заявок на перевозку',
                'kz' => 'Тасымалдауға өтініштер құру',
                'cn' => '创建运输申请',

            ],
            [
                'key' => 'welcome.track_delivery',
                'ru' => 'Отслеживание статуса доставки',
                'kz' => 'Жеткізу күйін қадағалау',
                'cn' => '跟踪交付状态',

            ],
            [
                'key' => 'welcome.route_management',
                'ru' => 'Управление маршрутами',
                'kz' => 'Маршруттарды басқару',
                'cn' => '路线管理',

            ],
            [
                'key' => 'welcome.car_management',
                'ru' => 'Управление машинами',
                'kz' => 'Машиналарды басқару',
                'cn' => '车辆管理',

            ],
            [
                'key' => 'welcome.car_management_desc',
                'ru' => 'Регистрация и управление автопарком водителей',
                'kz' => 'Жүргізушілер автопаркін тіркеу және басқару',
                'cn' => '司机车队注册和管理',

            ],
            [
                'key' => 'welcome.register_cars',
                'ru' => 'Регистрация машин и прицепов',
                'kz' => 'Машиналар мен тіркемелерді тіркеу',
                'cn' => '车辆和拖车注册',

            ],
            [
                'key' => 'welcome.technical_specs',
                'ru' => 'Учет технических характеристик',
                'kz' => 'Техникалық сипаттамаларды есепке алу',
                'cn' => '技术规格记录',

            ],
            [
                'key' => 'welcome.upload_docs',
                'ru' => 'Загрузка документов ПДД',
                'kz' => 'Жол қауіпсіздігі қағидалары құжаттарын жүктеу',
                'cn' => '上传交通规则文件',

            ],
            [
                'key' => 'welcome.user_management',
                'ru' => 'Управление пользователями',
                'kz' => 'Пайдаланушыларды басқару',
                'cn' => '用户管理',

            ],
            [
                'key' => 'welcome.user_management_desc',
                'ru' => 'Роли и права доступа в системе',
                'kz' => 'Жүйедегі рөлдер мен қол жеткізу құқықтары',
                'cn' => '系统中的角色和访问权限',

            ],
            [
                'key' => 'welcome.admins',
                'ru' => 'Администраторы',
                'kz' => 'Әкімшілер',
                'cn' => '管理员',

            ],
            [
                'key' => 'welcome.warehouse_workers',
                'ru' => 'Складские работники',
                'kz' => 'Қойма жұмысшылары',
                'cn' => '仓库工人',

            ],
            [
                'key' => 'welcome.drivers',
                'ru' => 'Водители',
                'kz' => 'Жүргізушілер',
                'cn' => '司机',

            ],
            [
                'key' => 'welcome.demo_title',
                'ru' => 'Демонстрация системы',
                'kz' => 'Жүйені көрсету',
                'cn' => '系统演示',

            ],
            [
                'key' => 'welcome.demo_description',
                'ru' => 'Для тестирования системы используйте следующие учетные данные:',
                'kz' => 'Жүйені сынау үшін келесі есептік деректерді пайдаланыңыз:',
                'cn' => '要测试系统，请使用以下凭据：',

            ],
            [
                'key' => 'welcome.test_drivers',
                'ru' => 'Тестовые водители:',
                'kz' => 'Сынақ жүргізушілері:',
                'cn' => '测试司机：',

            ],
            
            // Аутентификация
            [
                'key' => 'auth.login',
                'ru' => 'Вход - Silk Way',
                'kz' => 'Кіру - Silk Way',
                'cn' => '登录 - Silk Way',

            ],
            [
                'key' => 'auth.email_placeholder',
                'ru' => 'Email адрес',
                'kz' => 'Email мекенжайы',
                'cn' => '邮箱地址',

            ],
            [
                'key' => 'auth.password_placeholder',
                'ru' => 'Пароль',
                'kz' => 'Құпия сөз',
                'cn' => '密码',

            ],
            [
                'key' => 'auth.login_button',
                'ru' => 'Войти',
                'kz' => 'Кіру',
                'cn' => '登录',

            ],
            [
                'key' => 'auth.no_account',
                'ru' => 'Нет аккаунта?',
                'kz' => 'Есептік жазба жоқ па?',
                'cn' => '没有账户？',

            ],
            [
                'key' => 'auth.register_link',
                'ru' => 'Зарегистрироваться',
                'kz' => 'Тіркелу',
                'cn' => '注册',

            ],
            [
                'key' => 'auth.register_title',
                'ru' => 'Регистрация - Silk Way',
                'kz' => 'Тіркеу - Silk Way',
                'cn' => '注册 - Silk Way',

            ],
            [
                'key' => 'auth.register_heading',
                'ru' => 'Регистрация',
                'kz' => 'Тіркеу',
                'cn' => '注册',

            ],
            [
                'key' => 'auth.register_desc',
                'ru' => 'Создайте аккаунт для работы в системе',
                'kz' => 'Жүйеде жұмыс істеу үшін есептік жазба құрыңыз',
                'cn' => '创建账户以在系统中工作',

            ],
            [
                'key' => 'auth.full_name',
                'ru' => 'Полное имя',
                'kz' => 'Толық аты',
                'cn' => '全名',

            ],
            [
                'key' => 'auth.password_confirmation',
                'ru' => 'Подтвердите пароль',
                'kz' => 'Құпия сөзді растаңыз',
                'cn' => '确认密码',

            ],
            [
                'key' => 'auth.select_role',
                'ru' => 'Выберите роль',
                'kz' => 'Рөлді таңдаңыз',
                'cn' => '选择角色',

            ],
            [
                'key' => 'auth.warehouse_employee',
                'ru' => 'Сотрудник склада',
                'kz' => 'Қойма қызметкері',
                'cn' => '仓库员工',

            ],
            [
                'key' => 'auth.driver_role',
                'ru' => 'Водитель',
                'kz' => 'Жүргізуші',
                'cn' => '司机',

            ],
            [
                'key' => 'auth.register_button',
                'ru' => 'Зарегистрироваться',
                'kz' => 'Тіркелу',
                'cn' => '注册',

            ],
            [
                'key' => 'auth.have_account',
                'ru' => 'Уже есть аккаунт?',
                'kz' => 'Есептік жазба бар ма?',
                'cn' => '已有账户？',

            ],
            [
                'key' => 'auth.login_link',
                'ru' => 'Войти',
                'kz' => 'Кіру',
                'cn' => '登录',

            ],
            
            // Страницы грузов
            [
                'key' => 'cargo.available_cargo',
                'ru' => 'Доступные грузы',
                'kz' => 'Қолжетімді жүктер',
                'cn' => '可用货物',

            ],
            [
                'key' => 'cargo.available_cargo_desc',
                'ru' => 'Список всех доступных для перевозки грузов',
                'kz' => 'Тасымалдауға қолжетімді барлық жүктер тізімі',
                'cn' => '所有可用于运输的货物列表',

            ],
            [
                'key' => 'cargo.add_cargo',
                'ru' => 'Добавить груз',
                'kz' => 'Жүк қосу',
                'cn' => '添加货物',

            ],
            [
                'key' => 'cargo.search_placeholder',
                'ru' => 'Поиск по маршруту или типу груза...',
                'kz' => 'Маршрут немесе жүк түрі бойынша іздеу...',
                'cn' => '按路线或货物类型搜索...',

            ],
            [
                'key' => 'cargo.all_statuses',
                'ru' => 'Все статусы',
                'kz' => 'Барлық күйлер',
                'cn' => '所有状态',

            ],
            [
                'key' => 'cargo.status_available',
                'ru' => 'Доступен',
                'kz' => 'Қолжетімді',
                'cn' => '可用',

            ],
            [
                // Kept for backward compatibility — the UI previously used this key for
                // cargo in 'in_progress' DB status. Use cargo.status_in_progress for new UI work.
                'key' => 'cargo.status_picked_up',
                'ru' => 'Забран',
                'kz' => 'Алынды',
                'cn' => '已取',

            ],
            [
                // Canonical translation key for DB status value 'in_progress'.
                // cargo.status_picked_up overlaps and is kept for backward compatibility.
                'key'         => 'cargo.status_in_progress',
                'ru'          => 'В пути',
                'kz'          => 'Жолда', // TODO: verify with native speaker
                'cn'          => '运输中', // TODO: verify with native speaker
                'group'       => 'cargo',
                'description' => 'Cargo status label for in_progress DB value. cargo.status_picked_up is an older alias kept for backward compatibility.',
            ],
            [
                'key' => 'cargo.status_delivered',
                'ru' => 'Доставлен',
                'kz' => 'Жеткізілді',
                'cn' => '已交付',

            ],
            [
                'key' => 'cargo.table_route',
                'ru' => 'Маршрут',
                'kz' => 'Маршрут',
                'cn' => '路线',

            ],
            [
                'key' => 'cargo.table_cargo',
                'ru' => 'Груз',
                'kz' => 'Жүк',
                'cn' => '货物',

            ],
            [
                'key' => 'cargo.table_readiness',
                'ru' => 'Готовность',
                'kz' => 'Дайындық',
                'cn' => '准备就绪',

            ],
            [
                'key' => 'cargo.table_status',
                'ru' => 'Статус',
                'kz' => 'Күй',
                'cn' => '状态',

            ],
            [
                'key' => 'cargo.table_created',
                'ru' => 'Создан',
                'kz' => 'Құрылды',
                'cn' => '已创建',

            ],
            [
                'key' => 'cargo.table_actions',
                'ru' => 'Действия',
                'kz' => 'Әрекеттер',
                'cn' => '操作',

            ],
            [
                'key' => 'cargo.no_cargo_found',
                'ru' => 'Грузы не найдены',
                'kz' => 'Жүктер табылмады',
                'cn' => '未找到货物',

            ],
            [
                'key' => 'cargo.no_cargo_desc',
                'ru' => 'В данный момент нет доступных для перевозки грузов',
                'kz' => 'Қазіргі уақытта тасымалдауға қолжетімді жүктер жоқ',
                'cn' => '目前没有可用于运输的货物',

            ],
            [
                'key' => 'cargo.change_search',
                'ru' => 'Попробуйте изменить параметры поиска',
                'kz' => 'Іздеу параметрлерін өзгертуге тырысыңыз',
                'cn' => '尝试更改搜索参数',

            ],
            [
                'key' => 'cargo.reset_filters',
                'ru' => 'Сбросить фильтры',
                'kz' => 'Сүзгілерді қалпына келтіру',
                'cn' => '重置筛选器',

            ],
            
            // Админ панель
            [
                'key' => 'admin.dashboard_title',
                'ru' => 'Админ-панель',
                'kz' => 'Әкімші панелі',
                'cn' => '管理面板',

            ],
            [
                'key' => 'admin.dashboard_desc',
                'ru' => 'Управление системой и пользователями',
                'kz' => 'Жүйе мен пайдаланушыларды басқару',
                'cn' => '系统和用户管理',

            ],
            [
                'key' => 'admin.total_cargo',
                'ru' => 'Всего грузов',
                'kz' => 'Барлығы жүктер',
                'cn' => '货物总数',

            ],
            [
                'key' => 'admin.available_cargo',
                'ru' => 'Доступные грузы',
                'kz' => 'Қолжетімді жүктер',
                'cn' => '可用货物',

            ],
            [
                'key' => 'admin.picked_up_cargo',
                'ru' => 'Забранные грузы',
                'kz' => 'Алынған жүктер',
                'cn' => '已取货物',

            ],
            [
                'key' => 'admin.pending_users',
                'ru' => 'Пользователи на подтверждение',
                'kz' => 'Бекіту күтудегі пайдаланушылар',
                'cn' => '等待确认的用户',

            ],
            [
                'key' => 'admin.pending_users_desc',
                'ru' => 'Подтвердите или отклоните заявки на регистрацию',
                'kz' => 'Тіркеу өтініштерін бекітіңіз немесе бас тартыңыз',
                'cn' => '确认或拒绝注册申请',

            ],
            [
                'key' => 'admin.approved_users',
                'ru' => 'Подтвержденные пользователи',
                'kz' => 'Бекітілген пайдаланушылар',
                'cn' => '已确认用户',

            ],
            [
                'key' => 'admin.approved_users_desc',
                'ru' => 'Активные пользователи системы',
                'kz' => 'Жүйенің белсенді пайдаланушылары',
                'cn' => '系统的活跃用户',

            ],
            [
                'key' => 'admin.approve',
                'ru' => 'Подтвердить',
                'kz' => 'Бекіту',
                'cn' => '确认',

            ],
            [
                'key' => 'admin.reject',
                'ru' => 'Отклонить',
                'kz' => 'Бас тарту',
                'cn' => '拒绝',

            ],
            [
                'key' => 'admin.toggle_approval',
                'ru' => 'Отозвать доступ',
                'kz' => 'Қол жеткізуді алып тастау',
                'cn' => '撤销访问权限',

            ],
            [
                'key' => 'admin.translations_button',
                'ru' => 'Переводы',
                'kz' => 'Аудармалар',
                'cn' => '翻译',

            ],
            [
                'key' => 'admin.translations_desc',
                'group' => 'admin',
                'ru' => 'Управление переводами интерфейса на русском, казахском и китайском',
                'kz' => 'Интерфейс аудармаларын орыс, қазақ және қытай тілдерінде басқару',
                'cn' => '管理界面翻译（俄语、哈萨克语、中文）',
                'description' => 'Subtitle on the admin translations list page',

            ],
            [
                'key' => 'admin.user_name',
                'ru' => 'Имя',
                'kz' => 'Аты',
                'cn' => '姓名',

            ],
            [
                'key' => 'admin.user_email',
                'ru' => 'Email',
                'kz' => 'Email',
                'cn' => '邮箱',

            ],
            [
                'key' => 'admin.user_role',
                'ru' => 'Роль',
                'kz' => 'Рөл',
                'cn' => '角色',

            ],
            [
                'key' => 'admin.user_actions',
                'ru' => 'Действия',
                'kz' => 'Әрекеттер',
                'cn' => '操作',

            ],
            [
                'key' => 'admin.administrator',
                'ru' => 'Администратор',
                'kz' => 'Әкімші',
                'cn' => '管理员',

            ],
            [
                'key' => 'admin.registered_at',
                'ru' => 'Зарегистрирован',
                'kz' => 'Тіркелген',
                'cn' => '已注册',

            ],
            [
                'key' => 'admin.approved_at',
                'ru' => 'Подтвержден',
                'kz' => 'Бекітілген',
                'cn' => '已确认',

            ],
            [
                'key' => 'admin.confirm_reject_user',
                'ru' => 'Отклонить этого пользователя?',
                'kz' => 'Бұл пайдаланушыны бас тарту керек пе?',
                'cn' => '拒绝这个用户？',

            ],
            [
                'key' => 'admin.confirm_delete_user',
                'ru' => 'Удалить этого пользователя?',
                'kz' => 'Бұл пайдаланушыны жою керек пе?',
                'cn' => '删除这个用户？',

            ],
            [
                'key' => 'admin.users_management_title',
                'ru' => 'Управление пользователями - Silk Way',
                'kz' => 'Пайдаланушыларды басқару - Silk Way',
                'cn' => '用户管理 - Silk Way',

            ],
            [
                'key' => 'admin.users_management_heading',
                'ru' => 'Управление пользователями',
                'kz' => 'Пайдаланушыларды басқару',
                'cn' => '用户管理',

            ],
            [
                'key' => 'admin.users_management_desc',
                'ru' => 'Просмотр и управление всеми пользователями системы',
                'kz' => 'Жүйедегі барлық пайдаланушыларды көру және басқару',
                'cn' => '查看和管理系统中的所有用户',

            ],
            [
                'key' => 'admin.table_user',
                'ru' => 'Пользователь',
                'kz' => 'Пайдаланушы',
                'cn' => '用户',

            ],
            [
                'key' => 'admin.table_role',
                'ru' => 'Роль',
                'kz' => 'Рөл',
                'cn' => '角色',

            ],
            [
                'key' => 'admin.table_status',
                'ru' => 'Статус',
                'kz' => 'Күй',
                'cn' => '状态',

            ],
            [
                'key' => 'admin.table_registration_date',
                'ru' => 'Дата регистрации',
                'kz' => 'Тіркелу күні',
                'cn' => '注册日期',

            ],
            [
                'key' => 'admin.status_approved',
                'ru' => 'Подтвержден',
                'kz' => 'Бекітілген',
                'cn' => '已确认',

            ],
            [
                'key' => 'admin.status_pending',
                'ru' => 'Ожидает подтверждения',
                'kz' => 'Бекітуді күтуде',
                'cn' => '等待确认',

            ],
            [
                'key' => 'admin.toggle_access_title',
                'ru' => 'Отозвать доступ',
                'kz' => 'Қол жеткізуді алып тастау',
                'cn' => '撤销访问权限',

            ],
            [
                'key' => 'admin.delete_user_title',
                'ru' => 'Удалить пользователя',
                'kz' => 'Пайдаланушыны жою',
                'cn' => '删除用户',

            ],
            [
                'key' => 'cargo.add_cargo_button',
                'ru' => 'Добавить груз',
                'kz' => 'Жүк қосу',
                'cn' => '添加货物',

            ],
            [
                'key' => 'cargo.search_placeholder',
                'ru' => 'Поиск по маршруту или типу груза...',
                'kz' => 'Маршрут немесе жүк түрі бойынша іздеу...',
                'cn' => '按路线或货物类型搜索...',

            ],
            [
                'key' => 'cargo.all_statuses',
                'ru' => 'Все статусы',
                'kz' => 'Барлық күйлер',
                'cn' => '所有状态',

            ],
            [
                'key' => 'cargo.status_available',
                'ru' => 'Доступен',
                'kz' => 'Қолжетімді',
                'cn' => '可用',

            ],
            [
                'key' => 'cargo.status_picked_up',
                'ru' => 'Забран',
                'kz' => 'Алынған',
                'cn' => '已取',

            ],
            [
                'key' => 'cargo.status_delivered',
                'ru' => 'Доставлен',
                'kz' => 'Жеткізілген',
                'cn' => '已送达',

            ],
            [
                'key' => 'cargo.filter_button',
                'ru' => 'Фильтровать',
                'kz' => 'Сүзгілеу',
                'cn' => '筛选',

            ],
            [
                'key' => 'cargo.table_route',
                'ru' => 'Маршрут',
                'kz' => 'Маршрут',
                'cn' => '路线',

            ],
            [
                'key' => 'cargo.table_cargo',
                'ru' => 'Груз',
                'kz' => 'Жүк',
                'cn' => '货物',

            ],
            [
                'key' => 'cargo.table_readiness',
                'ru' => 'Готовность',
                'kz' => 'Дайындық',
                'cn' => '准备状态',

            ],
            [
                'key' => 'cargo.table_status',
                'ru' => 'Статус',
                'kz' => 'Күй',
                'cn' => '状态',

            ],
            [
                'key' => 'cargo.table_created',
                'ru' => 'Создан',
                'kz' => 'Құрылған',
                'cn' => '创建时间',

            ],
            [
                'key' => 'cargo.volume_weight',
                'ru' => 'м³, кг',
                'kz' => 'м³, кг',
                'cn' => '立方米, 公斤',

            ],
            [
                'key' => 'cargo.view_button',
                'ru' => 'Просмотр',
                'kz' => 'Көру',
                'cn' => '查看',

            ],
            [
                'key' => 'cargo.no_cargo_found',
                'ru' => 'Грузы не найдены',
                'kz' => 'Жүктер табылмады',
                'cn' => '未找到货物',

            ],
            [
                'key' => 'cargo.no_cargo_desc',
                'ru' => 'В данный момент нет доступных грузов для перевозки',
                'kz' => 'Қазіргі уақытта тасымалдауға қолжетімді жүктер жоқ',
                'cn' => '目前没有可运输的货物',

            ],
            [
                'key' => 'cargo.try_change_search',
                'ru' => 'Попробуйте изменить параметры поиска',
                'kz' => 'Іздеу параметрлерін өзгертуге тырысыңыз',
                'cn' => '尝试更改搜索参数',

            ],
            [
                'key' => 'cargo.reset_filters',
                'ru' => 'Сбросить фильтры',
                'kz' => 'Сүзгілерді қалпына келтіру',
                'cn' => '重置筛选器',

            ],
            [
                'key' => 'cargo.confirm_delete',
                'ru' => 'Удалить этот груз?',
                'kz' => 'Бұл жүкті жою керек пе?',
                'cn' => '删除这个货物？',

            ],
            [
                'key' => 'cargo.volume_label',
                'ru' => 'Объем:',
                'kz' => 'Көлемі:',
                'cn' => '体积：',

            ],
            [
                'key' => 'cargo.weight_label',
                'ru' => 'Вес:',
                'kz' => 'Салмағы:',
                'cn' => '重量：',

            ],
            [
                'key' => 'cargo.readiness_label',
                'ru' => 'Готовность:',
                'kz' => 'Дайындық:',
                'cn' => '准备状态：',

            ],
            [
                'key' => 'cargo.created_label',
                'ru' => 'Создан:',
                'kz' => 'Құрылған:',
                'cn' => '创建时间：',

            ],
            [
                'key' => 'applications.title',
                'ru' => 'Заявки на грузы',
                'kz' => 'Жүктерге өтініштер',
                'cn' => '货物申请',

            ],
            [
                'key' => 'applications.heading',
                'ru' => 'Заявки на грузы',
                'kz' => 'Жүктерге өтініштер',
                'cn' => '货物申请',

            ],
            [
                'key' => 'applications.admin_desc',
                'ru' => 'Все заявки в системе',
                'kz' => 'Жүйедегі барлық өтініштер',
                'cn' => '系统中的所有申请',

            ],
            [
                'key' => 'applications.driver_desc',
                'ru' => 'Заявки на ваши грузы',
                'kz' => 'Сіздің жүктеріңізге өтініштер',
                'cn' => '您货物的申请',

            ],
            [
                'key' => 'applications.status_label',
                'ru' => 'Статус',
                'kz' => 'Күй',
                'cn' => '状态',

            ],
            [
                'key' => 'applications.all_statuses',
                'ru' => 'Все статусы',
                'kz' => 'Барлық күйлер',
                'cn' => '所有状态',

            ],
            [
                'key' => 'applications.status_pending',
                'ru' => 'Ожидает рассмотрения',
                'kz' => 'Қарастыруды күтуде',
                'cn' => '等待审核',

            ],
            [
                'key' => 'applications.status_approved',
                'ru' => 'Подтверждена',
                'kz' => 'Бекітілген',
                'cn' => '已确认',

            ],
            [
                'key' => 'applications.status_rejected',
                'ru' => 'Отклонена',
                'kz' => 'Бас тартылған',
                'cn' => '已拒绝',

            ],
            [
                'key' => 'applications.status_delivered',
                'ru' => 'Доставлена',
                'kz' => 'Жеткізілді',
                'cn' => '已送达',

            ],
            [
                'key' => 'applications.search_label',
                'ru' => 'Поиск',
                'kz' => 'Іздеу',
                'cn' => '搜索',

            ],
            [
                'key' => 'applications.search_placeholder',
                'ru' => 'Поиск по маршруту или водителю',
                'kz' => 'Маршрут немесе жүргізуші бойынша іздеу',
                'cn' => '按路线或司机搜索',

            ],
            [
                'key' => 'applications.search_button',
                'ru' => 'Поиск',
                'kz' => 'Іздеу',
                'cn' => '搜索',

            ],
            [
                'key' => 'applications.table_route',
                'ru' => 'Маршрут',
                'kz' => 'Маршрут',
                'cn' => '路线',

            ],
            [
                'key' => 'applications.table_driver',
                'ru' => 'Водитель',
                'kz' => 'Жүргізуші',
                'cn' => '司机',

            ],
            [
                'key' => 'applications.table_status',
                'ru' => 'Статус',
                'kz' => 'Күй',
                'cn' => '状态',

            ],
            [
                'key' => 'applications.table_submitted',
                'ru' => 'Подана',
                'kz' => 'Берілген',
                'cn' => '提交时间',

            ],
            [
                'key' => 'applications.table_actions',
                'ru' => 'Действия',
                'kz' => 'Әрекеттер',
                'cn' => '操作',

            ],
            [
                'key' => 'applications.status_pending_short',
                'ru' => 'Ожидает',
                'kz' => 'Күтуде',
                'cn' => '等待中',

            ],
            [
                'key' => 'applications.status_approved_short',
                'ru' => 'Подтверждена',
                'kz' => 'Бекітілген',
                'cn' => '已确认',

            ],
            [
                'key' => 'applications.status_rejected_short',
                'ru' => 'Отклонена',
                'kz' => 'Бас тартылған',
                'cn' => '已拒绝',

            ],
            [
                'key' => 'applications.status_delivered_short',
                'ru' => 'Доставлена',
                'kz' => 'Жеткізілді',
                'cn' => '已送达',

            ],
            [
                'key' => 'applications.view_details',
                'ru' => 'Подробнее',
                'kz' => 'Толығырақ',
                'cn' => '详情',

            ],
            [
                'key' => 'applications.approve_button',
                'ru' => 'Подтвердить',
                'kz' => 'Бекіту',
                'cn' => '确认',

            ],
            [
                'key' => 'applications.reject_button',
                'ru' => 'Отклонить',
                'kz' => 'Бас тарту',
                'cn' => '拒绝',

            ],
            [
                'key' => 'applications.driver_label',
                'ru' => 'Водитель:',
                'kz' => 'Жүргізуші:',
                'cn' => '司机：',

            ],
            [
                'key' => 'applications.submitted_label',
                'ru' => 'Подана:',
                'kz' => 'Берілген:',
                'cn' => '提交时间：',

            ],
            [
                'key' => 'applications.confirm_approve',
                'ru' => 'Подтвердить заявку этого водителя?',
                'kz' => 'Бұл жүргізушінің өтінішін бекіту керек пе?',
                'cn' => '确认这个司机的申请？',

            ],
            [
                'key' => 'applications.confirm_reject',
                'ru' => 'Отклонить заявку этого водителя?',
                'kz' => 'Бұл жүргізушінің өтінішін бас тарту керек пе?',
                'cn' => '拒绝这个司机的申请？',

            ],
            [
                'key' => 'applications.no_applications',
                'ru' => 'Заявок пока нет',
                'kz' => 'Әзірше өтініштер жоқ',
                'cn' => '暂无申请',

            ],
            [
                'key' => 'applications.no_applications_desc',
                'ru' => 'Когда водители будут подавать заявки на грузы, они появятся здесь',
                'kz' => 'Жүргізушілер жүктерге өтініш бергенде, олар осында пайда болады',
                'cn' => '当司机申请货物时，它们将出现在这里',

            ],
            [
                'key' => 'applications.back_button',
                'ru' => 'Назад',
                'kz' => 'Артқа',
                'cn' => '返回',

            ],
            [
                'key' => 'applications.approved_by_label',
                'ru' => 'Подтвердил:',
                'kz' => 'Бекіткен:',
                'cn' => '审核人：',

            ],
            [
                'key' => 'applications.clear_search',
                'ru' => 'Сбросить',
                'kz' => 'Тазалау',
                'cn' => '清除',

            ],
            [
                'key' => 'applications.show_all_link',
                'ru' => 'Показать все заявки',
                'kz' => 'Барлық өтініштерді көрсету',
                'cn' => '显示所有申请',

            ],
            [
                'key' => 'applications.no_pending',
                'ru' => 'Нет заявок в ожидании',
                'kz' => 'Күтудегі өтініштер жоқ',
                'cn' => '暂无待审核申请',

            ],
            [
                'key' => 'applications.no_pending_desc',
                'ru' => 'Все заявки обработаны — отлично!',
                'kz' => 'Барлық өтініштер өңделді — тамаша!',
                'cn' => '所有申请均已处理，干得好！',

            ],
            [
                'key' => 'applications.no_approved',
                'ru' => 'Нет подтверждённых заявок',
                'kz' => 'Бекітілген өтініштер жоқ',
                'cn' => '暂无已确认申请',

            ],
            [
                'key' => 'applications.no_approved_desc',
                'ru' => 'Подтверждённые заявки будут отображаться здесь',
                'kz' => 'Бекітілген өтініштер осында көрсетіледі',
                'cn' => '已确认的申请将显示在这里',

            ],
            [
                'key' => 'applications.no_rejected',
                'ru' => 'Нет отклонённых заявок',
                'kz' => 'Бас тартылған өтініштер жоқ',
                'cn' => '暂无已拒绝申请',

            ],
            [
                'key' => 'applications.no_rejected_desc',
                'ru' => 'Отклонённые заявки будут отображаться здесь',
                'kz' => 'Бас тартылған өтініштер осында көрсетіледі',
                'cn' => '被拒绝的申请将显示在这里',

            ],
            [
                'key' => 'applications.no_search_results',
                'ru' => 'Ничего не найдено',
                'kz' => 'Ештеңе табылмады',
                'cn' => '未找到结果',

            ],
            [
                'key' => 'applications.no_search_results_desc',
                'ru' => 'По вашему запросу заявок не найдено. Попробуйте изменить поисковый запрос.',
                'kz' => 'Сіздің сұрауыңыз бойынша өтініштер табылмады. Іздеу сұрауын өзгертіп көріңіз.',
                'cn' => '未找到与您搜索条件匹配的申请，请尝试修改搜索词。',

            ],
            [
                'key' => 'my_cargo.title',
                'ru' => 'Мои грузы',
                'kz' => 'Менің жүктерім',
                'cn' => '我的货物',

            ],
            [
                'key' => 'my_cargo.heading',
                'ru' => 'Мои грузы',
                'kz' => 'Менің жүктерім',
                'cn' => '我的货物',

            ],
            [
                'key' => 'my_cargo.description',
                'ru' => 'Грузы, которые вы забрали для доставки',
                'kz' => 'Сіз жеткізу үшін алған жүктер',
                'cn' => '您已取走用于交付的货物',

            ],
            [
                'key' => 'my_cargo.back_button',
                'ru' => 'Назад',
                'kz' => 'Артқа',
                'cn' => '返回',

            ],
            [
                'key' => 'my_cargo.table_route',
                'ru' => 'Маршрут',
                'kz' => 'Маршрут',
                'cn' => '路线',

            ],
            [
                'key' => 'my_cargo.table_cargo',
                'ru' => 'Груз',
                'kz' => 'Жүк',
                'cn' => '货物',

            ],
            [
                'key' => 'my_cargo.table_picked',
                'ru' => 'Забран',
                'kz' => 'Алынған',
                'cn' => '已取',

            ],
            [
                'key' => 'my_cargo.table_status',
                'ru' => 'Статус',
                'kz' => 'Күй',
                'cn' => '状态',

            ],
            [
                'key' => 'my_cargo.status_in_delivery',
                'ru' => 'В доставке',
                'kz' => 'Жеткізуде',
                'cn' => '运输中',

            ],
            [
                'key' => 'my_cargo.status_delivered',
                'ru' => 'Доставлен',
                'kz' => 'Жеткізілген',
                'cn' => '已送达',

            ],
            [
                'key' => 'my_cargo.mark_delivered',
                'ru' => 'Доставлен',
                'kz' => 'Жеткізілген',
                'cn' => '已送达',

            ],
            [
                'key' => 'my_cargo.confirm_mark_delivered',
                'ru' => 'Отметить груз как доставленный?',
                'kz' => 'Жүкті жеткізілген деп белгілеу керек пе?',
                'cn' => '将货物标记为已送达？',

            ],
            [
                'key' => 'my_cargo.view_button',
                'ru' => 'Просмотр',
                'kz' => 'Көру',
                'cn' => '查看',

            ],
            [
                'key' => 'my_cargo.volume_label',
                'ru' => 'Объем:',
                'kz' => 'Көлемі:',
                'cn' => '体积：',

            ],
            [
                'key' => 'my_cargo.weight_label',
                'ru' => 'Вес:',
                'kz' => 'Салмағы:',
                'cn' => '重量：',

            ],
            [
                'key' => 'my_cargo.picked_label',
                'ru' => 'Забран:',
                'kz' => 'Алынған:',
                'cn' => '已取：',

            ],
            [
                'key' => 'my_cargo.no_cargo_title',
                'ru' => 'У вас пока нет забранных грузов',
                'kz' => 'Сізде әзірше алынған жүктер жоқ',
                'cn' => '您目前没有已取的货物',

            ],
            [
                'key' => 'my_cargo.no_cargo_desc',
                'ru' => 'Заберите груз для доставки, чтобы он появился в этом списке',
                'kz' => 'Жеткізуге жүк алыңыз, ол осы тізімде пайда болады',
                'cn' => '取走货物进行交付，它将出现在此列表中',

            ],
            [
                'key' => 'my_cargo.stats_total_picked',
                'ru' => 'Всего забрано',
                'kz' => 'Барлығы алынған',
                'cn' => '总共已取',

            ],
            [
                'key' => 'my_cargo.stats_in_delivery',
                'ru' => 'В доставке',
                'kz' => 'Жеткізуде',
                'cn' => '运输中',

            ],
            [
                'key' => 'my_cargo.stats_delivered',
                'ru' => 'Доставлено',
                'kz' => 'Жеткізілген',
                'cn' => '已送达',

            ],
            [
                'key' => 'my_applications.title',
                'ru' => 'Мои заявки',
                'kz' => 'Менің өтініштерім',
                'cn' => '我的申请',

            ],
            [
                'key' => 'my_applications.heading',
                'ru' => 'Мои заявки',
                'kz' => 'Менің өтініштерім',
                'cn' => '我的申请',

            ],
            [
                'key' => 'my_applications.description',
                'ru' => 'Отслеживайте статус ваших заявок на перевозку грузов',
                'kz' => 'Жүктерді тасымалдауға арналған өтініштеріңіздің күйін қадағалаңыз',
                'cn' => '跟踪您货物运输申请的状态',

            ],
            [
                'key' => 'my_applications.view_cargo_button',
                'ru' => 'Посмотреть грузы',
                'kz' => 'Жүктерді көру',
                'cn' => '查看货物',

            ],
            [
                'key' => 'my_applications.stats_pending',
                'ru' => 'Ожидают',
                'kz' => 'Күтуде',
                'cn' => '等待中',

            ],
            [
                'key' => 'my_applications.stats_approved',
                'ru' => 'Подтверждены',
                'kz' => 'Бекітілген',
                'cn' => '已确认',

            ],
            [
                'key' => 'my_applications.stats_rejected',
                'ru' => 'Отклонены',
                'kz' => 'Бас тартылған',
                'cn' => '已拒绝',

            ],
            [
                'key' => 'my_applications.pending_title',
                'ru' => 'Ожидающие заявки',
                'kz' => 'Күтудегі өтініштер',
                'cn' => '等待中的申请',

            ],
            [
                'key' => 'my_applications.approved_title',
                'ru' => 'Подтвержденные заявки',
                'kz' => 'Бекітілген өтініштер',
                'cn' => '已确认的申请',

            ],
            [
                'key' => 'my_applications.rejected_title',
                'ru' => 'Отклоненные заявки',
                'kz' => 'Бас тартылған өтініштер',
                'cn' => '已拒绝的申请',

            ],
            [
                'key' => 'my_applications.table_route',
                'ru' => 'Маршрут',
                'kz' => 'Маршрут',
                'cn' => '路线',

            ],
            [
                'key' => 'my_applications.table_cargo',
                'ru' => 'Груз',
                'kz' => 'Жүк',
                'cn' => '货物',

            ],
            [
                'key' => 'my_applications.table_submitted',
                'ru' => 'Подана',
                'kz' => 'Берілген',
                'cn' => '提交时间',

            ],
            [
                'key' => 'my_applications.table_actions',
                'ru' => 'Действия',
                'kz' => 'Әрекеттер',
                'cn' => '操作',

            ],
            [
                'key' => 'my_applications.status_pending',
                'ru' => 'Ожидает',
                'kz' => 'Күтуде',
                'cn' => '等待中',

            ],
            [
                'key' => 'my_applications.status_approved',
                'ru' => 'Подтверждено',
                'kz' => 'Бекітілген',
                'cn' => '已确认',

            ],
            [
                'key' => 'my_applications.status_rejected',
                'ru' => 'Отклонено',
                'kz' => 'Бас тартылған',
                'cn' => '已拒绝',

            ],
            [
                'key' => 'my_applications.status_delivered',
                'ru' => 'Доставлено',
                'kz' => 'Жеткізілді',
                'cn' => '已送达',

            ],
            [
                'key' => 'my_applications.view_details',
                'ru' => 'Подробнее',
                'kz' => 'Толығырақ',
                'cn' => '详情',

            ],
            [
                'key' => 'my_applications.volume_weight',
                'ru' => 'м³, кг',
                'kz' => 'м³, кг',
                'cn' => '立方米, 公斤',

            ],
            [
                'key' => 'my_applications.volume_label',
                'ru' => 'Объем:',
                'kz' => 'Көлемі:',
                'cn' => '体积：',

            ],
            [
                'key' => 'my_applications.weight_label',
                'ru' => 'Вес:',
                'kz' => 'Салмағы:',
                'cn' => '重量：',

            ],
            [
                'key' => 'my_applications.submitted_label',
                'ru' => 'Подана:',
                'kz' => 'Берілген:',
                'cn' => '提交时间：',

            ],
            [
                'key' => 'my_applications.driver_notes_label',
                'ru' => 'Ваши заметки:',
                'kz' => 'Сіздің ескертпелеріңіз:',
                'cn' => '您的备注：',

            ],
            [
                'key' => 'my_applications.no_applications_title',
                'ru' => 'У вас пока нет заявок',
                'kz' => 'Сізде әзірше өтініштер жоқ',
                'cn' => '您目前没有申请',

            ],
            [
                'key' => 'my_applications.no_applications_desc',
                'ru' => 'Подайте заявку на любой доступный груз, чтобы начать работу',
                'kz' => 'Жұмысты бастау үшін кез келген қолжетімді жүкке өтініш беріңіз',
                'cn' => '申请任何可用的货物开始工作',

            ],
            [
                'key' => 'my_applications.view_available_cargo',
                'ru' => 'Посмотреть доступные грузы',
                'kz' => 'Қолжетімді жүктерді көру',
                'cn' => '查看可用货物',

            ],
            [
                'key' => 'my_applications.back_button',
                'ru' => 'Назад',
                'kz' => 'Артқа',
                'cn' => '返回',

            ],
            [
                'key' => 'admin.delete_user',
                'ru' => 'Удалить',
                'kz' => 'Жою',
                'cn' => '删除',

            ],
            [
                'key' => 'admin.confirm_delete',
                'ru' => 'Удалить этого пользователя?',
                'kz' => 'Бұл пайдаланушыны жою керек пе?',
                'cn' => '删除此用户？',

            ],
            [
                'key' => 'admin.confirm_reject',
                'ru' => 'Отклонить этого пользователя?',
                'kz' => 'Бұл пайдаланушыны бас тарту керек пе?',
                'cn' => '拒绝此用户？',

            ],
            [
                'key' => 'admin.warehouse_employee',
                'ru' => 'Сотрудник склада',
                'kz' => 'Қойма қызметкері',
                'cn' => '仓库员工',

            ],
            [
                'key' => 'admin.driver',
                'ru' => 'Водитель',
                'kz' => 'Жүргізуші',
                'cn' => '司机',

            ],
            [
                'key' => 'admin.administrator',
                'ru' => 'Администратор',
                'kz' => 'Әкімші',
                'cn' => '管理员',

            ],
            [
                'key' => 'admin.registered',
                'ru' => 'Зарегистрирован:',
                'kz' => 'Тіркелген:',
                'cn' => '已注册：',

            ],
            [
                'key' => 'admin.approved',
                'ru' => 'Подтвержден:',
                'kz' => 'Бекітілген:',
                'cn' => '已确认：',

            ],
            
            // Страницы заявок
            [
                'key' => 'applications.title',
                'ru' => 'Заявки на грузы',
                'kz' => 'Жүктерге өтініштер',
                'cn' => '货物申请',

            ],
            [
                'key' => 'applications.all_applications',
                'ru' => 'Все заявки в системе',
                'kz' => 'Жүйедегі барлық өтініштер',
                'cn' => '系统中的所有申请',

            ],
            [
                'key' => 'applications.your_cargo_applications',
                'ru' => 'Заявки на ваши грузы',
                'kz' => 'Сіздің жүктеріңізге өтініштер',
                'cn' => '您货物的申请',

            ],
            [
                'key' => 'applications.status_label',
                'ru' => 'Статус',
                'kz' => 'Күй',
                'cn' => '状态',

            ],
            [
                'key' => 'applications.search_label',
                'ru' => 'Поиск',
                'kz' => 'Іздеу',
                'cn' => '搜索',

            ],
            [
                'key' => 'applications.search_placeholder',
                'ru' => 'Поиск по маршруту или водителю',
                'kz' => 'Маршрут немесе жүргізуші бойынша іздеу',
                'cn' => '按路线或司机搜索',

            ],
            [
                'key' => 'applications.search_button',
                'ru' => 'Поиск',
                'kz' => 'Іздеу',
                'cn' => '搜索',

            ],
            [
                'key' => 'applications.table_route',
                'ru' => 'Маршрут',
                'kz' => 'Маршрут',
                'cn' => '路线',

            ],
            [
                'key' => 'applications.table_driver',
                'ru' => 'Водитель',
                'kz' => 'Жүргізуші',
                'cn' => '司机',

            ],
            [
                'key' => 'applications.table_status',
                'ru' => 'Статус',
                'kz' => 'Күй',
                'cn' => '状态',

            ],
            [
                'key' => 'applications.table_submitted',
                'ru' => 'Подана',
                'kz' => 'Ұсынылды',
                'cn' => '已提交',

            ],
            [
                'key' => 'applications.table_actions',
                'ru' => 'Действия',
                'kz' => 'Әрекеттер',
                'cn' => '操作',

            ],
            [
                'key' => 'applications.status_pending',
                'ru' => 'Ожидает',
                'kz' => 'Күтуде',
                'cn' => '等待中',

            ],
            [
                'key' => 'applications.status_approved',
                'ru' => 'Подтверждена',
                'kz' => 'Бекітілді',
                'cn' => '已确认',

            ],
            [
                'key' => 'applications.status_rejected',
                'ru' => 'Отклонена',
                'kz' => 'Қабылданбады',
                'cn' => '已拒绝',

            ],
            [
                'key' => 'applications.no_applications',
                'ru' => 'Заявки не найдены',
                'kz' => 'Өтініштер табылмады',
                'cn' => '未找到申请',

            ],
            [
                'key' => 'applications.no_applications_desc',
                'ru' => 'В данный момент нет заявок на перевозку',
                'kz' => 'Қазіргі уақытта тасымалдауға өтініштер жоқ',
                'cn' => '目前没有运输申请',

            ],
            [
                'key' => 'applications.cargo_already_taken',
                'group' => 'applications',
                'ru' => 'Груз уже взят другим водителем. Заявка отклонена.',
                'kz' => 'Жүкті басқа жүргізуші алды. Өтінім қабылданбады.',
                'cn' => '货物已被其他司机接单。申请被拒绝。',

            ],
            [
                'key' => 'applications.already_applied',
                'group' => 'applications',
                'ru' => 'Вы уже подали заявку на этот груз.',
                'kz' => 'Сіз бұл жүкке өтінім берген екенсіз.',
                'cn' => '您已经为此货物提交了申请。',

            ],
            [
                'key' => 'applications.has_active_cargo',
                'group' => 'applications',
                'ru' => 'У вас уже есть активный груз. Завершите текущую доставку, прежде чем подавать новые заявки.',
                'kz' => 'Сізде белсенді жүк бар. Жаңа өтінімдерді бермес бұрын ағымдағы жеткізуді аяқтаңыз.',
                'cn' => '您已有进行中的货运任务。请先完成当前配送再申请新货物。',

            ],
            [
                'key' => 'applications.no_car_first',
                'group' => 'applications',
                'ru' => 'Сначала добавьте автомобиль в профиле, затем подавайте заявки на грузы.',
                'kz' => 'Алдымен профильге көлік қосыңыз, содан кейін жүктерге өтінім беріңіз.', // TODO: verify with native speaker
                'cn' => '请先在个人资料中添加车辆，然后再申请货物。', // TODO: verify with native speaker
                'description' => 'Driver tries to apply for cargo but has no cars registered',
            ],
            [
                'key' => 'applications.documents_required',
                'group' => 'applications',
                'ru' => 'Подайте все обязательные документы и дождитесь подтверждения администратора, чтобы откликаться на грузы.',
                'kz' => 'Жүктерге өтінім беру үшін барлық міндетті құжаттарды тапсырыңыз және әкімшінің растауын күтіңіз.', // TODO: verify with native speaker
                'cn' => '在申请货物之前，请上传所有必需的文件并等待管理员确认。', // TODO: verify with native speaker
                'description' => 'Driver tries to apply but has missing/unverified required documents',
            ],

            // Формы создания и редактирования грузов
            [
                'key' => 'cargo.create_title',
                'ru' => 'Добавить груз - Silk Way',
                'kz' => 'Жүк қосу - Silk Way',
                'cn' => '添加货物 - Silk Way',

            ],
            [
                'key' => 'cargo.new_cargo',
                'ru' => 'Новый груз',
                'kz' => 'Жаңа жүк',
                'cn' => '新货物',

            ],
            [
                'key' => 'cargo.create_desc',
                'ru' => 'Заполните информацию о грузе для отправки',
                'kz' => 'Жіберуге арналған жүк туралы ақпаратты толтырыңыз',
                'cn' => '填写要发送的货物信息',

            ],
            [
                'key' => 'cargo.from_location',
                'ru' => 'Откуда',
                'kz' => 'Қайдан',
                'cn' => '从哪里',

            ],
            [
                'key' => 'cargo.to_location',
                'ru' => 'Куда',
                'kz' => 'Қайда',
                'cn' => '到哪里',

            ],
            [
                'key' => 'cargo.cargo_type',
                'ru' => 'Тип груза',
                'kz' => 'Жүк түрі',
                'cn' => '货物类型',

            ],
            [
                'key' => 'cargo.volume',
                'ru' => 'Объем (м³)',
                'kz' => 'Көлем (м³)',
                'cn' => '体积 (m³)',

            ],
            [
                'key' => 'cargo.weight',
                'ru' => 'Вес (кг)',
                'kz' => 'Салмақ (кг)',
                'cn' => '重量 (kg)',

            ],
            [
                'key' => 'cargo.ready_date',
                'ru' => 'Дата и время готовности',
                'kz' => 'Дайындық күні мен уақыты',
                'cn' => '准备就绪的日期和时间',

            ],
            [
                'key' => 'cargo.comment',
                'ru' => 'Комментарий / контакт',
                'kz' => 'Түсініктеме / байланыс',
                'cn' => '评论/联系',

            ],
            [
                'key' => 'cargo.comment_placeholder',
                'ru' => 'Дополнительная информация, контактные данные...',
                'kz' => 'Қосымша ақпарат, байланыс деректері...',
                'cn' => '附加信息、联系信息...',

            ],
            [
                'key' => 'cargo.cancel',
                'ru' => 'Отмена',
                'kz' => 'Бас тарту',
                'cn' => '取消',

            ],
            [
                'key' => 'cargo.create_cargo',
                'ru' => 'Создать груз',
                'kz' => 'Жүк құру',
                'cn' => '创建货物',

            ],
            [
                'key' => 'cargo.edit_title',
                'ru' => 'Редактировать груз - Silk Way',
                'kz' => 'Жүкті өңдеу - Silk Way',
                'cn' => '编辑货物 - Silk Way',

            ],
            [
                'key' => 'cargo.edit_cargo',
                'ru' => 'Редактировать груз',
                'kz' => 'Жүкті өңдеу',
                'cn' => '编辑货物',

            ],
            [
                'key' => 'cargo.update_cargo',
                'ru' => 'Обновить груз',
                'kz' => 'Жүкті жаңарту',
                'cn' => '更新货物',

            ],
            
            // Формы создания и редактирования машин
            [
                'key' => 'cars.create_title',
                'ru' => 'Добавить машину - Silk Way',
                'kz' => 'Машина қосу - Silk Way',
                'cn' => '添加车辆 - Silk Way',

            ],
            [
                'key' => 'cars.new_car',
                'ru' => 'Новая машина',
                'kz' => 'Жаңа машина',
                'cn' => '新车辆',

            ],
            [
                'key' => 'cars.create_desc',
                'ru' => 'Заполните информацию о машине и прицепе',
                'kz' => 'Машина мен тіркеме туралы ақпаратты толтырыңыз',
                'cn' => '填写车辆和拖车信息',

            ],
            [
                'key' => 'cars.brand',
                'ru' => 'Марка',
                'kz' => 'Марка',
                'cn' => '品牌',

            ],
            [
                'key' => 'cars.model',
                'ru' => 'Модель',
                'kz' => 'Модель',
                'cn' => '型号',

            ],
            [
                'key' => 'cars.license_plate',
                'ru' => 'Гос. номер',
                'kz' => 'Мемлекеттік нөмір',
                'cn' => '车牌号',

            ],
            [
                'key' => 'cars.max_weight',
                'ru' => 'Макс. вес (т)',
                'kz' => 'Макс. салмақ (т)',
                'cn' => '最大重量 (t)',

            ],
            [
                'key' => 'cars.trailer_type',
                'ru' => 'Тип прицепа',
                'kz' => 'Тіркеме түрі',
                'cn' => '拖车类型',

            ],
            [
                'key' => 'cars.trailer_length',
                'ru' => 'Длина прицепа (м)',
                'kz' => 'Тіркеме ұзындығы (м)',
                'cn' => '拖车长度 (m)',

            ],
            [
                'key' => 'cars.trailer_width',
                'ru' => 'Ширина прицепа (м)',
                'kz' => 'Тіркеме ені (м)',
                'cn' => '拖车宽度 (m)',

            ],
            [
                'key' => 'cars.trailer_height',
                'ru' => 'Высота прицепа (м)',
                'kz' => 'Тіркеме биіктігі (м)',
                'cn' => '拖车高度 (m)',

            ],
            [
                'key' => 'cars.vehicle_document',
                'ru' => 'Документ ПДД (PDF)',
                'kz' => 'Жол қауіпсіздігі қағидалары құжаты (PDF)',
                'cn' => '交通规则文件 (PDF)',

            ],
            [
                'key' => 'cars.create_car',
                'ru' => 'Создать машину',
                'kz' => 'Машина құру',
                'cn' => '创建车辆',

            ],
            [
                'key' => 'cars.edit_title',
                'ru' => 'Редактировать машину - Silk Way',
                'kz' => 'Машинаны өңдеу - Silk Way',
                'cn' => '编辑车辆 - Silk Way',

            ],
            [
                'key' => 'cars.update_car',
                'ru' => 'Обновить машину',
                'kz' => 'Машинаны жаңарту',
                'cn' => '更新车辆',

            ],
            
            // Общие элементы и сообщения
            [
                'key' => 'common.success',
                'ru' => 'Успешно!',
                'kz' => 'Сәтті!',
                'cn' => '成功！',

            ],
            [
                'key' => 'common.error',
                'ru' => 'Ошибка!',
                'kz' => 'Қате!',
                'cn' => '错误！',

            ],
            [
                'key' => 'common.warning',
                'ru' => 'Внимание!',
                'kz' => 'Назар аударыңыз!',
                'cn' => '注意！',

            ],
            [
                'key' => 'common.info',
                'ru' => 'Информация',
                'kz' => 'Ақпарат',
                'cn' => '信息',

            ],
            [
                'key' => 'common.confirm',
                'ru' => 'Подтверждение',
                'kz' => 'Растау',
                'cn' => '确认',

            ],
            [
                'key' => 'common.yes',
                'ru' => 'Да',
                'kz' => 'Иә',
                'cn' => '是',

            ],
            [
                'key' => 'common.no',
                'ru' => 'Нет',
                'kz' => 'Жоқ',
                'cn' => '否',

            ],
            [
                'key' => 'common.loading',
                'ru' => 'Загрузка...',
                'kz' => 'Жүктелуде...',
                'cn' => '加载中...',

            ],
            [
                'key' => 'common.no_data',
                'ru' => 'Нет данных',
                'kz' => 'Деректер жоқ',
                'cn' => '无数据',

            ],
            [
                'key' => 'common.actions',
                'ru' => 'Действия',
                'kz' => 'Әрекеттер',
                'cn' => '操作',

            ],
            [
                'key' => 'common.view',
                'ru' => 'Просмотр',
                'kz' => 'Көру',
                'cn' => '查看',

            ],
            [
                'key' => 'common.edit',
                'ru' => 'Редактировать',
                'kz' => 'Өңдеу',
                'cn' => '编辑',

            ],
            [
                'key' => 'common.delete',
                'ru' => 'Удалить',
                'kz' => 'Жою',
                'cn' => '删除',

            ],
            [
                'key' => 'common.back',
                'ru' => 'Назад',
                'kz' => 'Артқа',
                'cn' => '返回',

            ],
            [
                'key' => 'common.close',
                'ru' => 'Закрыть',
                'kz' => 'Жабу',
                'cn' => '关闭',

            ],
            [
                'key' => 'brand',
                'ru' => 'Марка',
                'kz' => 'Марка',
                'cn' => '品牌',

            ],
            [
                'key' => 'model',
                'ru' => 'Модель',
                'kz' => 'Модель',
                'cn' => '型号',

            ],
            [
                'key' => 'license_plate',
                'ru' => 'Гос. номер',
                'kz' => 'Мемлекеттік нөмір',
                'cn' => '车牌号',

            ],
            [
                'key' => 'max_weight',
                'ru' => 'Макс. вес',
                'kz' => 'Макс. салмақ',
                'cn' => '最大重量',

            ],
            [
                'key' => 'trailer_type',
                'ru' => 'Тип прицепа',
                'kz' => 'Тіркеме түрі',
                'cn' => '拖车类型',

            ],
            [
                'key' => 'trailer_length',
                'ru' => 'Длина прицепа',
                'kz' => 'Тіркеме ұзындығы',
                'cn' => '拖车长度',

            ],
            [
                'key' => 'trailer_width',
                'ru' => 'Ширина прицепа',
                'kz' => 'Тіркеме ені',
                'cn' => '拖车宽度',

            ],
            [
                'key' => 'trailer_height',
                'ru' => 'Высота прицепа',
                'kz' => 'Тіркеме биіктігі',
                'cn' => '拖车高度',

            ],
            [
                'key' => 'trailer_volume',
                'ru' => 'Объем прицепа',
                'kz' => 'Тіркеме көлемі',
                'cn' => '拖车体积',

            ],
            
            // Переводы для пользователей
            [
                'key' => 'user',
                'ru' => 'Пользователь',
                'kz' => 'Пайдаланушы',
                'cn' => '用户',

            ],
            [
                'key' => 'name',
                'ru' => 'Имя',
                'kz' => 'Аты',
                'cn' => '姓名',

            ],
            [
                'key' => 'role',
                'ru' => 'Роль',
                'kz' => 'Рөл',
                'cn' => '角色',

            ],
            [
                'key' => 'admin',
                'ru' => 'Администратор',
                'kz' => 'Әкімші',
                'cn' => '管理员',

            ],
            [
                'key' => 'driver',
                'ru' => 'Водитель',
                'kz' => 'Жүргізуші',
                'cn' => '司机',

            ],
            [
                'key' => 'warehouse_employee',
                'ru' => 'Сотрудник склада',
                'kz' => 'Қойма қызметкері',
                'cn' => '仓库员工',

            ],
            [
                'key' => 'approved',
                'ru' => 'Одобрен',
                'kz' => 'Бекітілді',
                'cn' => '已批准',

            ],
            [
                'key' => 'pending_approval',
                'ru' => 'Ожидает одобрения',
                'kz' => 'Бекіту күтуде',
                'cn' => '等待批准',

            ],
            
            // Переводы для заявок
            [
                'key' => 'application',
                'ru' => 'Заявка',
                'kz' => 'Өтініш',
                'cn' => '申请',

            ],
            [
                'key' => 'pending',
                'ru' => 'Ожидает',
                'kz' => 'Күтуде',
                'cn' => '等待中',

            ],
            [
                'key' => 'approved',
                'ru' => 'Одобрено',
                'kz' => 'Бекітілді',
                'cn' => '已批准',

            ],
            [
                'key' => 'rejected',
                'ru' => 'Отклонено',
                'kz' => 'Қабылданбады',
                'cn' => '已拒绝',

            ],
            
            // Переводы для админки
            [
                'key' => 'dashboard',
                'ru' => 'Панель управления',
                'kz' => 'Басқару панелі',
                'cn' => '控制面板',

            ],
            [
                'key' => 'translations',
                'ru' => 'Переводы',
                'kz' => 'Аудармалар',
                'cn' => '翻译',

            ],
            [
                'key' => 'manage_translations',
                'ru' => 'Управление переводами',
                'kz' => 'Аудармаларды басқару',
                'cn' => '管理翻译',

            ],
            [
                'key' => 'add_translation',
                'ru' => 'Добавить перевод',
                'kz' => 'Аударма қосу',
                'cn' => '添加翻译',

            ],
            [
                'key' => 'edit_translation',
                'ru' => 'Редактировать перевод',
                'kz' => 'Аударманы өңдеу',
                'cn' => '编辑翻译',

            ],
            [
                'key' => 'translation_key',
                'ru' => 'Ключ перевода',
                'kz' => 'Аударма кілті',
                'cn' => '翻译键',

            ],
            [
                'key' => 'translation_group',
                'ru' => 'Группа',
                'kz' => 'Топ',
                'cn' => '组',

            ],
            [
                'key' => 'translation_description',
                'ru' => 'Описание',
                'kz' => 'Сипаттама',
                'cn' => '描述',

            ],
            [
                'key' => 'russian',
                'ru' => 'Русский',
                'kz' => 'Орысша',
                'cn' => '俄语',

            ],
            [
                'key' => 'kazakh',
                'ru' => 'Казахский',
                'kz' => 'Қазақша',
                'cn' => '哈萨克语',

            ],
            [
                'key' => 'chinese',
                'ru' => 'Китайский',
                'kz' => 'Қытайша',
                'cn' => '中文',

            ],

            // Переводы для хедера и навигации
            [
                'key' => 'header.admin_panel',
                'ru' => 'Админ-панель',
                'kz' => 'Админ панель',
                'cn' => '管理面板',

            ],
            [
                'key' => 'header.users',
                'ru' => 'Пользователи',
                'kz' => 'Пайдаланушылар',
                'cn' => '用户',

            ],
            [
                'key' => 'header.cargo',
                'ru' => 'Грузы',
                'kz' => 'Жүктер',
                'cn' => '货物',

            ],
            [
                'key' => 'header.add_cargo',
                'ru' => 'Добавить груз',
                'kz' => 'Жүк қосу',
                'cn' => '添加货物',

            ],
            [
                'key' => 'header.applications',
                'ru' => 'Заявки',
                'kz' => 'Өтініштер',
                'cn' => '申请',

            ],
            [
                'key' => 'header.all_cars',
                'ru' => 'Все машины',
                'kz' => 'Барлық көліктер',
                'cn' => '所有车辆',

            ],
            [
                'key' => 'header.my_cargo',
                'ru' => 'Мои грузы',
                'kz' => 'Менің жүктерім',
                'cn' => '我的货物',

            ],
            [
                'key' => 'header.my_applications',
                'ru' => 'Мои заявки',
                'kz' => 'Менің өтініштерім',
                'cn' => '我的申请',

            ],
            [
                'key' => 'header.my_cars',
                'ru' => 'Мои машины',
                'kz' => 'Менің көліктерім',
                'cn' => '我的车辆',

            ],
            [
                'key' => 'header.logout',
                'ru' => 'Выйти',
                'kz' => 'Шығу',
                'cn' => '退出',

            ],
            [
                'key' => 'header.role_admin',
                'ru' => 'Админ',
                'kz' => 'Админ',
                'cn' => '管理员',

            ],
            [
                'key' => 'header.role_warehouse',
                'ru' => 'Склад',
                'kz' => 'Қойма',
                'cn' => '仓库',

            ],
            [
                'key' => 'header.role_driver',
                'ru' => 'Водитель',
                'kz' => 'Жүргізуші',
                'cn' => '司机',

            ],
            [
                'key' => 'header.profile',
                'ru' => 'Профиль',
                'kz' => 'Профиль',
                'cn' => '个人资料',

            ],
            [
                'key' => 'header.cars',
                'ru' => 'Машины',
                'kz' => 'Көліктер',
                'cn' => '车辆',

            ],
            [
                'key' => 'header.footer_text',
                'ru' => 'Система управления грузоперевозками.',
                'kz' => 'Жүк тасымалдау басқару жүйесі.',
                'cn' => '货运管理系统。',

            ],
            
            // Дополнительные переводы для аутентификации
            [
                'key' => 'auth.email',
                'ru' => 'Email',
                'kz' => 'Email',
                'cn' => '邮箱',

            ],
            [
                'key' => 'auth.password',
                'ru' => 'Пароль',
                'kz' => 'Құпия сөз',
                'cn' => '密码',

            ],
            [
                'key' => 'auth.full_name_placeholder',
                'ru' => 'Введите ваше полное имя',
                'kz' => 'Толық атыңызды енгізіңіз',
                'cn' => '请输入您的全名',

            ],
            [
                'key' => 'auth.password_confirmation',
                'ru' => 'Подтверждение пароля',
                'kz' => 'Құпия сөзді растау',
                'cn' => '确认密码',

            ],
            [
                'key' => 'auth.password_confirmation_placeholder',
                'ru' => 'Подтвердите пароль',
                'kz' => 'Құпия сөзді растаңыз',
                'cn' => '请确认密码',

            ],
            [
                'key' => 'auth.password_show',
                'ru' => 'Показать пароль',
                'kz' => 'Құпия сөзді көрсету', // TODO: verify with native speaker
                'cn' => '显示密码', // TODO: verify with native speaker

            ],
            [
                'key' => 'auth.password_hide',
                'ru' => 'Скрыть пароль',
                'kz' => 'Құпия сөзді жасыру', // TODO: verify with native speaker
                'cn' => '隐藏密码', // TODO: verify with native speaker

            ],
            [
                'key' => 'auth.select_role',
                'ru' => 'Выберите роль',
                'kz' => 'Рөлді таңдаңыз',
                'cn' => '选择角色',

            ],
            
            // Дополнительные переводы для админ-панели
            [
                'key' => 'admin.add_translation',
                'ru' => 'Добавить перевод',
                'kz' => 'Аударма қосу',
                'cn' => '添加翻译',

            ],
            [
                'key' => 'admin.export',
                'ru' => 'Экспорт',
                'kz' => 'Экспорт',
                'cn' => '导出',

            ],
            [
                'key' => 'admin.clear_cache',
                'ru' => 'Очистить кэш',
                'kz' => 'Кэшті тазалау',
                'cn' => '清除缓存',

            ],
            [
                'key' => 'admin.search_by_key',
                'ru' => 'Поиск по ключу',
                'kz' => 'Кілт бойынша іздеу',
                'cn' => '按键搜索',

            ],
            [
                'key' => 'admin.search_placeholder',
                'ru' => 'Введите ключ перевода...',
                'kz' => 'Аударма кілтін енгізіңіз...',
                'cn' => '输入翻译键...',

            ],
            [
                'key' => 'admin.all_groups',
                'ru' => 'Все группы',
                'kz' => 'Барлық топтар',
                'cn' => '所有组',

            ],
            [
                'key' => 'admin.filter',
                'ru' => 'Фильтр',
                'kz' => 'Сүзгі',
                'cn' => '过滤',

            ],
            [
                'key' => 'admin.clear_filters',
                'ru' => 'Сбросить фильтры',
                'kz' => 'Сүзгілерді тазалау',
                'cn' => '清除过滤器',

            ],
            [
                'key' => 'admin.table_key',
                'ru' => 'Ключ',
                'kz' => 'Кілт',
                'cn' => '键',

            ],
            [
                'key' => 'admin.table_russian',
                'ru' => 'Русский',
                'kz' => 'Орысша',
                'cn' => '俄语',

            ],
            [
                'key' => 'admin.table_kazakh',
                'ru' => 'Қазақша',
                'kz' => 'Қазақша',
                'cn' => '哈萨克语',

            ],
            [
                'key' => 'admin.table_chinese',
                'ru' => '中文',
                'kz' => 'Қытайша',
                'cn' => '中文',

            ],
            [
                'key' => 'admin.table_group',
                'ru' => 'Группа',
                'kz' => 'Топ',
                'cn' => '组',

            ],
            [
                'key' => 'admin.table_actions',
                'ru' => 'Действия',
                'kz' => 'Әрекеттер',
                'cn' => '操作',

            ],
            [
                'key' => 'admin.no_translations_found',
                'ru' => 'Переводы не найдены',
                'kz' => 'Аудармалар табылмады',
                'cn' => '未找到翻译',

            ],
            [
                'key' => 'admin.try_change_search',
                'ru' => 'Попробуйте изменить параметры поиска',
                'kz' => 'Іздеу параметрлерін өзгертіп көріңіз',
                'cn' => '尝试更改搜索参数',

            ],
            [
                'key' => 'admin.no_translations_desc',
                'ru' => 'В данный момент нет переводов в системе',
                'kz' => 'Қазіргі уақытта жүйеде аудармалар жоқ',
                'cn' => '目前系统中没有翻译',

            ],
            [
                'key' => 'admin.reset_filters',
                'ru' => 'Сбросить фильтры',
                'kz' => 'Сүзгілерді тазалау',
                'cn' => '重置过滤器',

            ],
            [
                'key' => 'admin.users_management_title',
                'ru' => 'Управление пользователями',
                'kz' => 'Пайдаланушыларды басқару',
                'cn' => '用户管理',

            ],
            [
                'key' => 'admin.users_management_heading',
                'ru' => 'Управление пользователями',
                'kz' => 'Пайдаланушыларды басқару',
                'cn' => '用户管理',

            ],
            [
                'key' => 'admin.users_management_desc',
                'ru' => 'Управление пользователями системы и их правами доступа',
                'kz' => 'Жүйе пайдаланушыларын және олардың қол жетімділік құқықтарын басқару',
                'cn' => '管理系统用户及其访问权限',

            ],
            [
                'key' => 'admin.table_user',
                'ru' => 'Пользователь',
                'kz' => 'Пайдаланушы',
                'cn' => '用户',

            ],
            [
                'key' => 'admin.table_role',
                'ru' => 'Роль',
                'kz' => 'Рөл',
                'cn' => '角色',

            ],
            [
                'key' => 'admin.table_status',
                'ru' => 'Статус',
                'kz' => 'Күй',
                'cn' => '状态',

            ],
            [
                'key' => 'admin.table_registration_date',
                'ru' => 'Дата регистрации',
                'kz' => 'Тіркелу күні',
                'cn' => '注册日期',

            ],
            [
                'key' => 'admin.administrator',
                'ru' => 'Администратор',
                'kz' => 'Әкімші',
                'cn' => '管理员',

            ],
            [
                'key' => 'admin.status_approved',
                'ru' => 'Подтвержден',
                'kz' => 'Расталды',
                'cn' => '已确认',

            ],
            [
                'key' => 'admin.status_pending',
                'ru' => 'Ожидает',
                'kz' => 'Күтуде',
                'cn' => '等待中',

            ],
            [
                'key' => 'admin.toggle_access_title',
                'ru' => 'Переключить доступ',
                'kz' => 'Қол жетімділікті ауыстыру',
                'cn' => '切换访问权限',

            ],
            [
                'key' => 'admin.delete_user_title',
                'ru' => 'Удалить пользователя',
                'kz' => 'Пайдаланушыны жою',
                'cn' => '删除用户',

            ],

            // Публичный каталог грузов (гостевые страницы)
            [
                'key'   => 'cargo.public.title',
                'ru'    => 'Доступные грузы',
                'kz'    => 'Қолжетімді жүктер', // TODO: проверить перевод
                'cn'    => '可用货物',            // TODO: проверить перевод
                'group' => 'cargo',
                'description' => 'Public cargo listing page title',
            ],
            [
                'key'   => 'cargo.public.login_to_see_rate',
                'ru'    => 'Войдите, чтобы увидеть ставку',
                'kz'    => 'Бағаны көру үшін кіріңіз', // TODO: проверить перевод
                'cn'    => '登录以查看价格',             // TODO: проверить перевод
                'group' => 'cargo',
                'description' => 'Amber pill shown instead of price for guests',
            ],
            [
                'key'   => 'cargo.public.apply_cta',
                'ru'    => 'Подать заявку на этот груз',
                'kz'    => 'Осы жүкке өтінім беру', // TODO: проверить перевод
                'cn'    => '申请此货物',              // TODO: проверить перевод
                'group' => 'cargo',
                'description' => 'Sticky CTA button on public detail page',
            ],
            [
                'key'   => 'cargo.public.filters',
                'ru'    => 'Фильтры',
                'kz'    => 'Сүзгілер', // TODO: проверить перевод
                'cn'    => '筛选条件', // TODO: проверить перевод
                'group' => 'cargo',
                'description' => 'Mobile filter pill label on public listing',
            ],
            [
                'key'   => 'cargo.public.from',
                'ru'    => 'Откуда',
                'kz'    => 'Қайдан', // TODO: проверить перевод
                'cn'    => '出发地', // TODO: проверить перевод
                'group' => 'cargo',
                'description' => 'From city filter label',
            ],
            [
                'key'   => 'cargo.public.to',
                'ru'    => 'Куда',
                'kz'    => 'Қайда', // TODO: проверить перевод
                'cn'    => '目的地', // TODO: проверить перевод
                'group' => 'cargo',
                'description' => 'To city filter label',
            ],
            [
                'key'   => 'cargo.public.no_results_title',
                'ru'    => 'Грузы не найдены',
                'kz'    => 'Жүктер табылмады', // TODO: проверить перевод
                'cn'    => '未找到货物',        // TODO: проверить перевод
                'group' => 'cargo',
                'description' => 'Empty state heading on public listing',
            ],
            [
                'key'   => 'cargo.public.no_results_cta',
                'ru'    => 'Сбросить фильтры',
                'kz'    => 'Сүзгілерді тазалау', // TODO: проверить перевод
                'cn'    => '清除筛选条件',         // TODO: проверить перевод
                'group' => 'cargo',
                'description' => 'Clear filters CTA in empty state',
            ],
            [
                'key'   => 'nav.login',
                'ru'    => 'Войти',
                'kz'    => 'Кіру',
                'cn'    => '登录',
                'group' => 'header',
                'description' => 'Top-bar login button for guests',
            ],
            [
                'key'   => 'cargo.ready_label',
                'ru'    => 'Готов к',
                'kz'    => 'Дайын',
                'cn'    => '准备时间',
                'group' => 'cargo',
                'description' => 'Inline label for ready date on cargo card',
            ],
            [
                'key'   => 'cargo.ready_date_label',
                'ru'    => 'Дата готовности',
                'kz'    => 'Дайындық күні',
                'cn'    => '准备日期',
                'group' => 'cargo',
                'description' => 'Ready date tile label on public detail',
            ],
            [
                'key'   => 'cargo.volume_weight_label',
                'ru'    => 'Объем и вес',
                'kz'    => 'Көлем және салмақ',
                'cn'    => '体积和重量',
                'group' => 'cargo',
                'description' => 'Volume + weight tile label on public detail',
            ],
            [
                'key'   => 'cargo.price_usd',
                'ru'    => 'Ставка',
                'kz'    => 'Баға',
                'cn'    => '价格',
                'group' => 'cargo',
                'description' => 'Price tile label on public detail',
            ],
            [
                'key'   => 'cargo.comment_label',
                'ru'    => 'Комментарий',
                'kz'    => 'Түсініктеме',
                'cn'    => '评论',
                'group' => 'cargo',
                'description' => 'Comment section heading on public detail',
            ],

            // Documents — status labels (also inserted by add_document_translations migration;
            // using updateOrCreate here ensures a fresh db:seed without migrations still works).
            [
                'key'         => 'docs.status_not_uploaded',
                'ru'          => 'Не загружен',
                'kz'          => 'Жүктелмеген',
                'cn'          => '未上传',
                'group'       => 'docs',
                'description' => 'Document status: file has not been uploaded yet',
            ],
            [
                'key'         => 'docs.status_pending',
                'ru'          => 'На проверке',
                'kz'          => 'Тексерілуде',
                'cn'          => '审核中',
                'group'       => 'docs',
                'description' => 'Document status: file uploaded and awaiting admin review',
            ],
            [
                'key'         => 'docs.status_verified',
                'ru'          => 'Проверен',
                'kz'          => 'Тексерілді',
                'cn'          => '已验证',
                'group'       => 'docs',
                'description' => 'Document status: admin has approved the document',
            ],
            [
                'key'         => 'docs.status_rejected',
                'ru'          => 'Отклонен',
                'kz'          => 'Қабылданбады',
                'cn'          => '已拒绝',
                'group'       => 'docs',
                'description' => 'Document status: admin has rejected the document',
            ],

            // Documents — missing keys not covered by the migration seeder
            [
                'key'         => 'docs.verified_count_separator',
                'ru'          => 'из',
                'kz'          => 'ішінен',
                'cn'          => '共',
                'group'       => 'docs',
                'description' => 'Separator word between verified count and total in completion pill (e.g. "3 из 5")',
            ],
            [
                'key'         => 'docs.verified_count_suffix',
                'ru'          => 'подтверждено',
                'kz'          => 'расталды',
                'cn'          => '已确认',
                'group'       => 'docs',
                'description' => 'Suffix word after the count in the completion pill (e.g. "3 из 5 подтверждено")',
            ],

            // Batch upload UI keys
            [
                'key'         => 'docs.batch_upload_button',
                'ru'          => 'Загрузить выбранные',
                'kz'          => 'Таңдалғандарды жүктеу',
                'cn'          => '上传所选文件',
                'group'       => 'docs',
                'description' => 'Label for the single batch submit button at bottom of documents page',
            ],
            [
                'key'         => 'docs.batch_selected_count',
                'ru'          => 'Выбрано файлов: :count',
                'kz'          => 'Таңдалған файлдар: :count',
                'cn'          => '已选择文件：:count',
                'group'       => 'docs',
                'description' => 'Live counter label shown next to batch submit button',
            ],
            [
                'key'         => 'docs.batch_no_files',
                'ru'          => 'Выберите хотя бы один файл для загрузки',
                'kz'          => 'Жүктеу үшін кем дегенде бір файл таңдаңыз',
                'cn'          => '请至少选择一个文件上传',
                'group'       => 'docs',
                'description' => 'Validation message when batch form submitted with zero files',
            ],
            [
                'key'         => 'docs.batch_success',
                'ru'          => 'Файлы успешно загружены',
                'kz'          => 'Файлдар сәтті жүктелді',
                'cn'          => '文件上传成功',
                'group'       => 'docs',
                'description' => 'Flash success message after all files in a batch upload successfully',
            ],
            [
                'key'         => 'docs.batch_partial_error',
                'ru'          => 'Не все файлы удалось загрузить. Смотрите ошибки ниже.',
                'kz'          => 'Барлық файлдарды жүктеу мүмкін болмады. Төмендегі қателерді қараңыз.',
                'cn'          => '部分文件上传失败，请查看下方错误信息。',
                'group'       => 'docs',
                'description' => 'Flash message when some slots succeed and some fail in a batch upload',
            ],

            // Admin document review page
            [
                'key'         => 'docs.admin_filter_all',
                'ru'          => 'Все',
                'kz'          => 'Барлығы',
                'cn'          => '全部',
                'group'       => 'docs',
                'description' => 'Admin documents filter tab — show all drivers',
            ],
            [
                'key'         => 'docs.admin_filter_pending',
                'ru'          => 'На проверке',
                'kz'          => 'Тексеруде',
                'cn'          => '待审核',
                'group'       => 'docs',
                'description' => 'Admin documents filter tab — drivers with pending documents',
            ],
            [
                'key'         => 'docs.admin_filter_rejected',
                'ru'          => 'Отклонённые',
                'kz'          => 'Қабылданбаған',
                'cn'          => '已拒绝',
                'group'       => 'docs',
                'description' => 'Admin documents filter tab — drivers with rejected documents',
            ],
            [
                'key'         => 'docs.admin_filter_verified',
                'ru'          => 'Подтверждённые',
                'kz'          => 'Расталған',
                'cn'          => '已确认',
                'group'       => 'docs',
                'description' => 'Admin documents filter tab — drivers with all verified documents',
            ],
            [
                'key'         => 'docs.admin_search_placeholder',
                'ru'          => 'Поиск по имени или email…',
                'kz'          => 'Атауы немесе email бойынша іздеу…',
                'cn'          => '按姓名或邮箱搜索…',
                'group'       => 'docs',
                'description' => 'Placeholder for admin documents search input',
            ],
            [
                'key'         => 'docs.admin_drivers_count',
                'ru'          => 'водителей',
                'kz'          => 'жүргізуші',
                'cn'          => '名司机',
                'group'       => 'docs',
                'description' => 'Suffix after driver count in admin documents page header',
            ],
            [
                'key'         => 'docs.admin_uploaded',
                'ru'          => 'Загружено',
                'kz'          => 'Жүктелді',
                'cn'          => '上传于',
                'group'       => 'docs',
                'description' => 'Label for document upload timestamp in admin review card',
            ],
            [
                'key'         => 'docs.admin_open_file',
                'ru'          => 'Открыть',
                'kz'          => 'Ашу',
                'cn'          => '打开',
                'group'       => 'docs',
                'description' => 'Link text to open PDF in new tab in admin review card',
            ],
            [
                'key'         => 'docs.admin_confirm_reject',
                'ru'          => 'Отклонить',
                'kz'          => 'Қабылдамау',
                'cn'          => '确认拒绝',
                'group'       => 'docs',
                'description' => 'Button label to confirm document rejection with reason in admin review',
            ],
            [
                'key'         => 'docs.admin_not_uploaded_yet',
                'ru'          => 'Документ не загружен',
                'kz'          => 'Құжат жүктелмеген',
                'cn'          => '文件未上传',
                'group'       => 'docs',
                'description' => 'Empty state text in admin review card when driver has not uploaded the document',
            ],
            [
                'key'         => 'docs.admin_empty_title',
                'ru'          => 'Водители не найдены',
                'kz'          => 'Жүргізушілер табылмады',
                'cn'          => '未找到司机',
                'group'       => 'docs',
                'description' => 'Heading for admin documents empty state when no drivers match filter/search',
            ],
            [
                'key'         => 'docs.admin_empty_desc',
                'ru'          => 'Попробуйте сбросить фильтры или изменить поисковый запрос',
                'kz'          => 'Сүзгілерді тазалап немесе іздеу сұранысын өзгертіп көріңіз',
                'cn'          => '请尝试清除筛选条件或修改搜索内容',
                'group'       => 'docs',
                'description' => 'Body text for admin documents empty state',
            ],
            [
                'key'         => 'docs.admin_clear_filters',
                'ru'          => 'Сбросить фильтры',
                'kz'          => 'Сүзгілерді тазалау',
                'cn'          => '清除筛选',
                'group'       => 'docs',
                'description' => 'CTA link in admin documents empty state to clear active filter/search',
            ],

            // ---------------------------------------------------------------
            // Document type labels and descriptions — mobile API contract
            // Key pattern: docs.type.{code}.label / docs.type.{code}.description
            // ---------------------------------------------------------------

            // driver_license
            [
                'key'         => 'docs.type.driver_license.label',
                'ru'          => 'Водительское удостоверение',
                'kz'          => 'Жүргізушілік куәлік', // TODO: verify with native speaker
                'cn'          => '驾驶证', // TODO: verify with native speaker
                'group'       => 'docs',
                'description' => 'Mobile API: localized label for driver_license document slot',
            ],
            [
                'key'         => 'docs.type.driver_license.description',
                'ru'          => 'Лицевая и обратная стороны вашего водительского удостоверения',
                'kz'          => 'Жүргізушілік куәліктің алдыңғы және артқы беттері', // TODO: verify
                'cn'          => '驾驶证正面和背面', // TODO: verify
                'group'       => 'docs',
                'description' => 'Mobile API: upload hint for driver_license slot',
            ],

            // vehicle_passport
            [
                'key'         => 'docs.type.vehicle_passport.label',
                'ru'          => 'Технический паспорт ТС',
                'kz'          => 'Көлік құралының техникалық паспорты', // TODO: verify
                'cn'          => '车辆技术证', // TODO: verify
                'group'       => 'docs',
                'description' => 'Mobile API: localized label for vehicle_passport document slot',
            ],
            [
                'key'         => 'docs.type.vehicle_passport.description',
                'ru'          => 'Технический паспорт вашего транспортного средства',
                'kz'          => 'Көлік құралыңыздың техникалық паспорты', // TODO: verify
                'cn'          => '您车辆的技术护照', // TODO: verify
                'group'       => 'docs',
                'description' => 'Mobile API: upload hint for vehicle_passport slot',
            ],

            // trailer_passport
            [
                'key'         => 'docs.type.trailer_passport.label',
                'ru'          => 'Технический паспорт прицепа',
                'kz'          => 'Тіркеменің техникалық паспорты', // TODO: verify
                'cn'          => '拖车技术证', // TODO: verify
                'group'       => 'docs',
                'description' => 'Mobile API: localized label for trailer_passport document slot',
            ],
            [
                'key'         => 'docs.type.trailer_passport.description',
                'ru'          => 'Технический паспорт прицепа или полуприцепа',
                'kz'          => 'Тіркеме немесе жартылай тіркеменің техникалық паспорты', // TODO: verify
                'cn'          => '拖车或半挂车的技术护照', // TODO: verify
                'group'       => 'docs',
                'description' => 'Mobile API: upload hint for trailer_passport slot',
            ],

            // category_cert
            [
                'key'         => 'docs.type.category_cert.label',
                'ru'          => 'Свидетельство о категории',
                'kz'          => 'Санат куәлігі', // TODO: verify
                'cn'          => '类别证书', // TODO: verify
                'group'       => 'docs',
                'description' => 'Mobile API: localized label for category_cert document slot',
            ],
            [
                'key'         => 'docs.type.category_cert.description',
                'ru'          => 'Свидетельство о допуске к перевозкам соответствующей категории грузов',
                'kz'          => 'Тиісті санаттағы жүктерді тасымалдауға рұқсат куәлігі', // TODO: verify
                'cn'          => '相应类别货物运输许可证书', // TODO: verify
                'group'       => 'docs',
                'description' => 'Mobile API: upload hint for category_cert slot',
            ],

            // green_card
            [
                'key'         => 'docs.type.green_card.label',
                'ru'          => 'Зелёная карта',
                'kz'          => 'Жасыл карта', // TODO: verify
                'cn'          => '绿卡保险', // TODO: verify
                'group'       => 'docs',
                'description' => 'Mobile API: localized label for green_card document slot',
            ],
            [
                'key'         => 'docs.type.green_card.description',
                'ru'          => 'Международная карта автострахования (Зелёная карта)',
                'kz'          => 'Халықаралық автокөлік сақтандыру картасы (Жасыл карта)', // TODO: verify
                'cn'          => '国际汽车保险卡（绿卡）', // TODO: verify
                'group'       => 'docs',
                'description' => 'Mobile API: upload hint for green_card slot',
            ],

            // insurance (optional)
            [
                'key'         => 'docs.type.insurance.label',
                'ru'          => 'Страховой полис',
                'kz'          => 'Сақтандыру полисі', // TODO: verify
                'cn'          => '保险单', // TODO: verify
                'group'       => 'docs',
                'description' => 'Mobile API: localized label for insurance document slot (optional)',
            ],
            [
                'key'         => 'docs.type.insurance.description',
                'ru'          => 'Страховой полис транспортного средства (необязательно)',
                'kz'          => 'Көлік құралының сақтандыру полисі (міндетті емес)', // TODO: verify
                'cn'          => '车辆保险单（可选）', // TODO: verify
                'group'       => 'docs',
                'description' => 'Mobile API: upload hint for insurance slot (optional)',
            ],

            // ---------------------------------------------------------------
            // Driver documents page — file preview & metadata keys
            // ---------------------------------------------------------------
            [
                'key'         => 'docs.open_pdf',
                'ru'          => 'Открыть PDF',
                'kz'          => 'PDF ашу',
                'cn'          => '打开PDF',
                'group'       => 'docs',
                'description' => 'Link label on PDF card in driver documents view to open file in new tab',
            ],
            [
                'key'         => 'docs.open_in_new_tab',
                'ru'          => 'Открыть в новой вкладке',
                'kz'          => 'Жаңа қойындыда ашу',
                'cn'          => '在新标签页中打开',
                'group'       => 'docs',
                'description' => 'Link label in driver documents view to open uploaded file in new browser tab',
            ],
            [
                'key'         => 'docs.uploaded_ago',
                'ru'          => 'Загружено',
                'kz'          => 'Жүктелді',
                'cn'          => '上传于',
                'group'       => 'docs',
                'description' => 'Label prefix for relative upload timestamp in driver documents card (e.g. "Загружено 5 минут назад")',
            ],
            [
                'key'         => 'docs.approved_ago',
                'ru'          => 'Подтверждено',
                'kz'          => 'Расталды',
                'cn'          => '已批准',
                'group'       => 'docs',
                'description' => 'Label prefix for relative approval timestamp in driver verified document card',
            ],
            [
                'key'         => 'docs.expires_in_days',
                'ru'          => 'Истекает через',
                'kz'          => 'Мерзімі аяқталады',
                'cn'          => '将于',
                'group'       => 'docs',
                'description' => 'Label for expiry countdown when document expires within 30 days, followed by day count',
            ],
            [
                'key'         => 'docs.expired_days_ago',
                'ru'          => 'Истёк',
                'kz'          => 'Мерзімі өтті',
                'cn'          => '已过期',
                'group'       => 'docs',
                'description' => 'Label shown when document expiry date is in the past, followed by days-ago count',
            ],
            [
                'key'         => 'docs.expires_at_label',
                'ru'          => 'Действует до',
                'kz'          => 'Дейін жарамды',
                'cn'          => '有效期至',
                'group'       => 'docs',
                'description' => 'Label for document expiry date when more than 30 days remain, followed by formatted date',
            ],
            [
                'key'         => 'docs.locked_by_admin',
                'ru'          => 'Документ подтверждён — замена недоступна',
                'kz'          => 'Құжат расталды — ауыстыру мүмкін емес',
                'cn'          => '文件已验证，无法替换',
                'group'       => 'docs',
                'description' => 'Lock notice on verified document card in driver view — explains why no re-upload is possible',
            ],

            // Admin translation detail page (show.blade.php)
            [
                'key'         => 'admin.translation_detail_title',
                'ru'          => 'Деталь перевода',
                'kz'          => 'Аударма мәліметі',
                'cn'          => '翻译详情',
                'group'       => 'admin',
                'description' => 'Browser tab title for admin translation detail page',
            ],
            [
                'key'         => 'admin.translation_detail_heading',
                'ru'          => 'Деталь перевода',
                'kz'          => 'Аударма мәліметі',
                'cn'          => '翻译详情',
                'group'       => 'admin',
                'description' => 'H1 heading on admin translation detail page',
            ],
            [
                'key'         => 'admin.translation_detail_desc',
                'ru'          => 'Просмотр ключа перевода и его вариантов на всех языках',
                'kz'          => 'Аударма кілтін және барлық тілдердегі нұсқаларын қарау',
                'cn'          => '查看翻译键及其所有语言版本',
                'group'       => 'admin',
                'description' => 'Subtitle on admin translation detail page',
            ],
            [
                'key'         => 'admin.translation_back_to_list',
                'ru'          => 'К списку переводов',
                'kz'          => 'Аудармалар тізіміне',
                'cn'          => '返回列表',
                'group'       => 'admin',
                'description' => 'Back breadcrumb link on admin translation detail page',
            ],
            [
                'key'         => 'admin.translation_key_label',
                'ru'          => 'Ключ',
                'kz'          => 'Кілт',
                'cn'          => '键',
                'group'       => 'admin',
                'description' => 'Label for translation key field on detail page',
            ],
            [
                'key'         => 'admin.translation_group_label',
                'ru'          => 'Группа',
                'kz'          => 'Топ',
                'cn'          => '分组',
                'group'       => 'admin',
                'description' => 'Label for group field on translation detail page',
            ],
            [
                'key'         => 'admin.translation_created_label',
                'ru'          => 'Создан',
                'kz'          => 'Жасалды',
                'cn'          => '创建时间',
                'group'       => 'admin',
                'description' => 'Label for created_at field on translation detail page',
            ],
            [
                'key'         => 'admin.translation_updated_label',
                'ru'          => 'Обновлён',
                'kz'          => 'Жаңартылды',
                'cn'          => '更新时间',
                'group'       => 'admin',
                'description' => 'Label for updated_at field on translation detail page',
            ],
            [
                'key'         => 'admin.translation_chars',
                'ru'          => 'симв.',
                'kz'          => 'таңба',
                'cn'          => '字符',
                'group'       => 'admin',
                'description' => 'Short label for character count in locale card on translation detail page',
            ],
            [
                'key'         => 'admin.translation_not_translated',
                'ru'          => 'Требуется перевод',
                'kz'          => 'Аударма қажет',
                'cn'          => '需要翻译',
                'group'       => 'admin',
                'description' => 'Empty-state heading inside a locale card when that locale value is blank',
            ],
            [
                'key'         => 'admin.translation_not_translated_hint',
                'ru'          => 'Значение ещё не заполнено',
                'kz'          => 'Мән әлі толтырылмаған',
                'cn'          => '该语言尚未填写',
                'group'       => 'admin',
                'description' => 'Empty-state sub-text inside a locale card when that locale value is blank',
            ],
            [
                'key'         => 'admin.translation_meta_heading',
                'ru'          => 'Метаинформация',
                'kz'          => 'Мета ақпарат',
                'cn'          => '元信息',
                'group'       => 'admin',
                'description' => 'Section label above the metadata grid on translation detail page',
            ],
            [
                'key'         => 'admin.translation_code_heading',
                'ru'          => 'Использование в коде',
                'kz'          => 'Кодта қолдану',
                'cn'          => '代码用法',
                'group'       => 'admin',
                'description' => 'Heading for code snippet section on translation detail page',
            ],
            [
                'key'         => 'admin.translation_code_desc',
                'ru'          => 'Скопируйте нужный фрагмент в свой шаблон или контроллер',
                'kz'          => 'Қажетті үзіндіні шаблонға немесе контроллерге көшіріңіз',
                'cn'          => '复制所需片段到模板或控制器',
                'group'       => 'admin',
                'description' => 'Subtitle for code snippet section on translation detail page',
            ],
            [
                'key'         => 'admin.translation_code_blade',
                'ru'          => 'Blade-шаблон',
                'kz'          => 'Blade-шаблон',
                'cn'          => 'Blade 模板',
                'group'       => 'admin',
                'description' => 'Label for the Blade snippet tab/row on translation detail page',
            ],
            [
                'key'         => 'admin.translation_code_php',
                'ru'          => 'Контроллер / PHP',
                'kz'          => 'Контроллер / PHP',
                'cn'          => '控制器 / PHP',
                'group'       => 'admin',
                'description' => 'Label for the PHP __() snippet row on translation detail page',
            ],
            [
                'key'         => 'admin.translation_code_helper',
                'ru'          => 'Хелпер',
                'kz'          => 'Хелпер',
                'cn'          => '辅助函数',
                'group'       => 'admin',
                'description' => 'Label for the LocalizationHelper::t() snippet row on translation detail page',
            ],
            [
                'key'         => 'admin.translation_code_copy',
                'ru'          => 'Копировать',
                'kz'          => 'Көшіру',
                'cn'          => '复制',
                'group'       => 'admin',
                'description' => 'Copy-to-clipboard button label on code snippet row in translation detail page',
            ],
            [
                'key'         => 'admin.translation_code_copied',
                'ru'          => 'Скопировано',
                'kz'          => 'Көшірілді',
                'cn'          => '已复制',
                'group'       => 'admin',
                'description' => 'Confirmation text briefly shown after copying a code snippet on translation detail page',
            ],
            [
                'key'         => 'admin.translation_code_in_blade',
                'ru'          => 'В шаблоне:',
                'kz'          => 'Шаблонда:',
                'cn'          => '模板中：',
                'group'       => 'admin',
                'description' => 'Comment label preceding the Blade snippet on translation detail page',
            ],
            [
                'key'         => 'admin.translation_code_in_controller',
                'ru'          => 'В контроллере:',
                'kz'          => 'Контроллерде:',
                'cn'          => '控制器中：',
                'group'       => 'admin',
                'description' => 'Comment label preceding the PHP __() snippet on translation detail page',
            ],
            [
                'key'         => 'admin.translation_code_in_helper',
                'ru'          => 'Через хелпер:',
                'kz'          => 'Хелпер арқылы:',
                'cn'          => '通过辅助函数：',
                'group'       => 'admin',
                'description' => 'Comment label preceding the LocalizationHelper snippet on translation detail page',
            ],

            // Admin translation create page (create.blade.php) & edit page (edit.blade.php)
            [
                'key'         => 'admin.translation_create_title',
                'ru'          => 'Новый перевод',
                'kz'          => 'Жаңа аударма', // TODO: verify with native speaker
                'cn'          => '新建翻译', // TODO: verify with native speaker
                'group'       => 'admin',
                'description' => 'Browser tab title and H1 heading on admin translation create page',
            ],
            [
                'key'         => 'admin.translation_create_desc',
                'ru'          => 'Добавьте новый ключ перевода с вариантами на всех языках',
                'kz'          => 'Барлық тілдерде аударма нұсқаларымен жаңа кілт қосыңыз', // TODO: verify with native speaker
                'cn'          => '添加新的翻译键及所有语言的翻译内容', // TODO: verify with native speaker
                'group'       => 'admin',
                'description' => 'Subtitle under the heading on admin translation create page',
            ],
            [
                'key'         => 'admin.translation_edit_title',
                'ru'          => 'Редактировать перевод',
                'kz'          => 'Аударманы өңдеу', // TODO: verify with native speaker
                'cn'          => '编辑翻译', // TODO: verify with native speaker
                'group'       => 'admin',
                'description' => 'Browser tab title and H1 heading on admin translation edit page',
            ],
            [
                'key'         => 'admin.translation_edit_desc',
                'ru'          => 'Обновите значения перевода для одного или нескольких языков',
                'kz'          => 'Бір немесе бірнеше тіл үшін аударма мәндерін жаңартыңыз', // TODO: verify with native speaker
                'cn'          => '更新一种或多种语言的翻译内容', // TODO: verify with native speaker
                'group'       => 'admin',
                'description' => 'Subtitle under the heading on admin translation edit page',
            ],
            [
                'key'         => 'admin.translation_section_meta',
                'ru'          => 'Идентификация',
                'kz'          => 'Идентификация', // TODO: verify with native speaker
                'cn'          => '标识信息', // TODO: verify with native speaker
                'group'       => 'admin',
                'description' => 'Section heading for key/group/description fields on create/edit page',
            ],
            [
                'key'         => 'admin.translation_section_locales',
                'ru'          => 'Переводы',
                'kz'          => 'Аудармалар', // TODO: verify with native speaker
                'cn'          => '翻译内容', // TODO: verify with native speaker
                'group'       => 'admin',
                'description' => 'Section heading for locale textarea cards on create/edit page',
            ],
            [
                'key'         => 'admin.translation_key_placeholder',
                'ru'          => 'section.subsection_name',
                'kz'          => 'section.subsection_name',
                'cn'          => 'section.subsection_name',
                'group'       => 'admin',
                'description' => 'Placeholder for the translation key input on create page',
            ],
            [
                'key'         => 'admin.translation_key_hint',
                'ru'          => 'Используйте точечные пространства имён. Должен быть уникальным.',
                'kz'          => 'Нүктелі аттар кеңістігін пайдаланыңыз. Бірегей болуы керек.', // TODO: verify with native speaker
                'cn'          => '使用点分命名空间，且必须唯一。', // TODO: verify with native speaker
                'group'       => 'admin',
                'description' => 'Helper hint below the key input on translation create page',
            ],
            [
                'key'         => 'admin.translation_key_readonly_hint',
                'ru'          => 'Ключ перевода нельзя изменить',
                'kz'          => 'Аударма кілтін өзгерту мүмкін емес', // TODO: verify with native speaker
                'cn'          => '翻译键不可修改', // TODO: verify with native speaker
                'group'       => 'admin',
                'description' => 'Helper hint below the readonly key input on translation edit page',
            ],
            [
                'key'         => 'admin.translation_group_placeholder',
                'ru'          => 'Выберите или введите группу',
                'kz'          => 'Топты таңдаңыз немесе енгізіңіз', // TODO: verify with native speaker
                'cn'          => '选择或输入分组', // TODO: verify with native speaker
                'group'       => 'admin',
                'description' => 'Placeholder for the group input on translation create/edit page',
            ],
            [
                'key'         => 'admin.translation_group_hint',
                'ru'          => 'Существующие группы показаны в подсказках. Введите новое имя, чтобы создать группу.',
                'kz'          => 'Бар топтар белгілерде көрсетілген. Жаңа топ жасау үшін жаңа атауды енгізіңіз.', // TODO: verify with native speaker
                'cn'          => '已有分组会作为建议显示。输入新名称可创建新分组。', // TODO: verify with native speaker
                'group'       => 'admin',
                'description' => 'Helper hint below the group input on translation create/edit page',
            ],
            [
                'key'         => 'admin.translation_description_label',
                'ru'          => 'Описание (для админов)',
                'kz'          => 'Сипаттама (әкімшілерге арналған)', // TODO: verify with native speaker
                'cn'          => '描述（供管理员查看）', // TODO: verify with native speaker
                'group'       => 'admin',
                'description' => 'Label for the description textarea on translation create/edit page',
            ],
            [
                'key'         => 'admin.translation_description_placeholder',
                'ru'          => 'Опишите, где используется этот ключ...',
                'kz'          => 'Бұл кілт қайда қолданылатынын сипаттаңыз...', // TODO: verify with native speaker
                'cn'          => '描述此键的使用位置...', // TODO: verify with native speaker
                'group'       => 'admin',
                'description' => 'Placeholder for description textarea on translation create/edit page',
            ],
            [
                'key'         => 'admin.translation_description_hint',
                'ru'          => 'Не отображается пользователям. Помогает будущим администраторам понять контекст ключа.',
                'kz'          => 'Пайдаланушыларға көрсетілмейді. Болашақ әкімшілерге кілттің мазмұнын түсінуге көмектеседі.', // TODO: verify with native speaker
                'cn'          => '不面向用户显示，帮助未来管理员理解此键的使用场景。', // TODO: verify with native speaker
                'group'       => 'admin',
                'description' => 'Helper hint below the description textarea on translation create/edit page',
            ],
            [
                'key'         => 'admin.translation_ru_label',
                'ru'          => 'Русский',
                'kz'          => 'Орысша', // TODO: verify with native speaker
                'cn'          => '俄语', // TODO: verify with native speaker
                'group'       => 'admin',
                'description' => 'Label for Russian locale textarea on translation create/edit page',
            ],
            [
                'key'         => 'admin.translation_kz_label',
                'ru'          => 'Қазақша',
                'kz'          => 'Қазақша',
                'cn'          => '哈萨克语', // TODO: verify with native speaker
                'group'       => 'admin',
                'description' => 'Label for Kazakh locale textarea on translation create/edit page',
            ],
            [
                'key'         => 'admin.translation_cn_label',
                'ru'          => '中文',
                'kz'          => '中文',
                'cn'          => '中文',
                'group'       => 'admin',
                'description' => 'Label for Chinese locale textarea on translation create/edit page',
            ],
            [
                'key'         => 'admin.translation_ru_placeholder',
                'ru'          => 'Текст на русском языке...',
                'kz'          => 'Орыс тіліндегі мәтін...', // TODO: verify with native speaker
                'cn'          => '俄语文本...', // TODO: verify with native speaker
                'group'       => 'admin',
                'description' => 'Placeholder for Russian textarea on translation create/edit page',
            ],
            [
                'key'         => 'admin.translation_kz_placeholder',
                'ru'          => 'Мәтін қазақша...',
                'kz'          => 'Мәтін қазақша...',
                'cn'          => '哈萨克语文本...', // TODO: verify with native speaker
                'group'       => 'admin',
                'description' => 'Placeholder for Kazakh textarea on translation create/edit page',
            ],
            [
                'key'         => 'admin.translation_cn_placeholder',
                'ru'          => '中文文本...',
                'kz'          => '中文文本...',
                'cn'          => '中文文本...',
                'group'       => 'admin',
                'description' => 'Placeholder for Chinese textarea on translation create/edit page',
            ],
            [
                'key'         => 'admin.translation_chars_suffix',
                'ru'          => 'симв.',
                'kz'          => 'таңба', // TODO: verify with native speaker
                'cn'          => '字符',
                'group'       => 'admin',
                'description' => 'Character count suffix shown in locale card header on create/edit page (live counter)',
            ],
            [
                'key'         => 'admin.translation_optional_badge',
                'ru'          => 'необязательно',
                'kz'          => 'міндетті емес', // TODO: verify with native speaker
                'cn'          => '可选', // TODO: verify with native speaker
                'group'       => 'admin',
                'description' => 'Optional badge shown in locale card header for KZ and CN on create/edit page',
            ],
            [
                'key'         => 'admin.translation_error_summary',
                'ru'          => 'Исправьте ошибки ниже',
                'kz'          => 'Төмендегі қателерді түзетіңіз', // TODO: verify with native speaker
                'cn'          => '请修正以下错误', // TODO: verify with native speaker
                'group'       => 'admin',
                'description' => 'Error summary banner heading on translation create/edit page when validation fails',
            ],
            [
                'key'         => 'admin.translation_error_count',
                'ru'          => 'Найдено ошибок: :count',
                'kz'          => 'Қателер табылды: :count', // TODO: verify with native speaker
                'cn'          => '发现 :count 个错误', // TODO: verify with native speaker
                'group'       => 'admin',
                'description' => 'Error count line in the validation error banner on translation create/edit page; :count is replaced at runtime',
            ],
            [
                'key'         => 'admin.translation_btn_save',
                'ru'          => 'Сохранить',
                'kz'          => 'Сақтау', // TODO: verify with native speaker
                'cn'          => '保存', // TODO: verify with native speaker
                'group'       => 'admin',
                'description' => 'Primary submit button on translation create/edit page',
            ],
            [
                'key'         => 'admin.translation_btn_cancel',
                'ru'          => 'Отмена',
                'kz'          => 'Болдырмау', // TODO: verify with native speaker
                'cn'          => '取消', // TODO: verify with native speaker
                'group'       => 'admin',
                'description' => 'Cancel/back button on translation create/edit page',
            ],

            // ─── Public landing page — hero ────────────────────────────────────
            [
                'key'         => 'public.hero_headline',
                'ru'          => 'Найдите груз. Зарегистрируйтесь и начните возить.',
                'kz'          => 'Жүк табыңыз. Тіркеліп, тасымалдауды бастаңыз.', // TODO: verify with native speaker
                'cn'          => '找到货物，注册并开始运输。', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Hero section main headline on public cargo listing',
            ],
            [
                'key'         => 'public.hero_subtitle',
                'ru'          => 'Silk Way — платформа для водителей и экспедиторов в Центральной Азии. Просматривайте грузы без регистрации, берите рейсы после проверки.',
                'kz'          => 'Silk Way — Орталық Азиядағы жүргізушілер мен экспедиторларға арналған платформа. Тіркелмей жүктерді қараңыз, тексеруден өткеннен кейін рейс алыңыз.', // TODO: verify with native speaker
                'cn'          => 'Silk Way 是中亚司机和货代的专属平台。无需注册即可浏览货物，审核通过后即可接单。', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Hero section supporting subtitle on public cargo listing',
            ],
            [
                'key'         => 'public.hero_cta_register',
                'ru'          => 'Создать аккаунт',
                'kz'          => 'Аккаунт жасау', // TODO: verify with native speaker
                'cn'          => '注册账号', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Primary CTA in hero — registration button',
            ],
            [
                'key'         => 'public.hero_cta_login',
                'ru'          => 'Войти',
                'kz'          => 'Кіру', // TODO: verify with native speaker
                'cn'          => '登录', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Secondary CTA in hero — login button',
            ],
            [
                'key'         => 'public.hero_trust_1',
                'ru'          => 'Бесплатно для водителей',
                'kz'          => 'Жүргізушілер үшін тегін', // TODO: verify with native speaker
                'cn'          => '司机免费使用', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'First trust bullet under hero CTAs',
            ],
            [
                'key'         => 'public.hero_trust_2',
                'ru'          => 'Одобрение за 24 часа',
                'kz'          => '24 сағатта мақұлдау', // TODO: verify with native speaker
                'cn'          => '24小时内审核', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Second trust bullet under hero CTAs',
            ],
            [
                'key'         => 'public.hero_trust_3',
                'ru'          => 'Русский, Казахский, Китайский',
                'kz'          => 'Орыс, Қазақ, Қытай тілдері', // TODO: verify with native speaker
                'cn'          => '支持俄语、哈萨克语、中文', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Third trust bullet under hero CTAs — multilingual support',
            ],

            // ─── Public landing page — how it works ───────────────────────────
            [
                'key'         => 'public.how_title',
                'ru'          => 'Как это работает',
                'kz'          => 'Бұл қалай жұмыс істейді', // TODO: verify with native speaker
                'cn'          => '如何运作', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Section heading for the "how it works" strip',
            ],
            [
                'key'         => 'public.how_step1_label',
                'ru'          => 'Шаг 1',
                'kz'          => '1-қадам', // TODO: verify with native speaker
                'cn'          => '第一步', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Step number label — step 1',
            ],
            [
                'key'         => 'public.how_step1_title',
                'ru'          => 'Просматривайте грузы',
                'kz'          => 'Жүктерді шолыңыз', // TODO: verify with native speaker
                'cn'          => '浏览货物', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Step 1 headline in how-it-works strip',
            ],
            [
                'key'         => 'public.how_step1_body',
                'ru'          => 'Фильтруйте по маршруту и дате — без регистрации.',
                'kz'          => 'Маршрут пен күн бойынша сүзіңіз — тіркелусіз.', // TODO: verify with native speaker
                'cn'          => '按路线和日期筛选，无需注册。', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Step 1 body text in how-it-works strip',
            ],
            [
                'key'         => 'public.how_step2_label',
                'ru'          => 'Шаг 2',
                'kz'          => '2-қадам', // TODO: verify with native speaker
                'cn'          => '第二步', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Step number label — step 2',
            ],
            [
                'key'         => 'public.how_step2_title',
                'ru'          => 'Зарегистрируйтесь и пройдите проверку',
                'kz'          => 'Тіркеліп, тексеруден өтіңіз', // TODO: verify with native speaker
                'cn'          => '注册并完成审核', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Step 2 headline in how-it-works strip',
            ],
            [
                'key'         => 'public.how_step2_body',
                'ru'          => 'Загрузите документы. Администратор одобрит аккаунт в течение 24 часов.',
                'kz'          => 'Құжаттарды жүктеңіз. Әкімші 24 сағат ішінде аккаунтты мақұлдайды.', // TODO: verify with native speaker
                'cn'          => '上传文件，管理员将在24小时内审核并批准账号。', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Step 2 body text in how-it-works strip',
            ],
            [
                'key'         => 'public.how_step3_label',
                'ru'          => 'Шаг 3',
                'kz'          => '3-қадам', // TODO: verify with native speaker
                'cn'          => '第三步', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Step number label — step 3',
            ],
            [
                'key'         => 'public.how_step3_title',
                'ru'          => 'Берите рейсы и зарабатывайте',
                'kz'          => 'Рейс алып, табыс табыңыз', // TODO: verify with native speaker
                'cn'          => '接单赚钱', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Step 3 headline in how-it-works strip',
            ],
            [
                'key'         => 'public.how_step3_body',
                'ru'          => 'Откликайтесь на грузы, видите ставку и договаривайтесь с отправителем.',
                'kz'          => 'Жүктерге жауап беріңіз, ставканы көріп, жөнелтушімен келісіңіз.', // TODO: verify with native speaker
                'cn'          => '响应货物需求，查看报价，与发货方协商。', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Step 3 body text in how-it-works strip',
            ],

            // ─── Public landing page — listings section ────────────────────────
            [
                'key'         => 'public.listings_title',
                'ru'          => 'Доступные грузы',
                'kz'          => 'Қолжетімді жүктер', // TODO: verify with native speaker
                'cn'          => '可用货物', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Subheading above cargo card grid on public listing',
            ],
            [
                'key'         => 'public.listings_subtitle',
                'ru'          => 'Просматривайте маршруты и подавайте заявку после регистрации',
                'kz'          => 'Маршруттарды шолыңыз және тіркелгеннен кейін өтінім беріңіз', // TODO: verify with native speaker
                'cn'          => '浏览路线，注册后即可申请', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Supporting line under listings section heading',
            ],
            [
                'key'         => 'public.listings_count_badge',
                'ru'          => 'сейчас в поиске водителя',
                'kz'          => 'қазір жүргізуші іздейді', // TODO: verify with native speaker
                'cn'          => '个货物正在寻找司机', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Suffix in the count badge next to listings heading (prepend the number)',
            ],

            // ─── Public landing page — empty state ────────────────────────────
            [
                'key'         => 'public.empty_title',
                'ru'          => 'Ничего не найдено',
                'kz'          => 'Ештеңе табылмады', // TODO: verify with native speaker
                'cn'          => '未找到结果', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Empty state heading when filters return no results',
            ],
            [
                'key'         => 'public.empty_body_filtered',
                'ru'          => 'Ничего не подходит по фильтрам. Попробуйте убрать даты или выбрать соседний город.',
                'kz'          => 'Фильтрлерге сәйкес ештеңе жоқ. Күндерді алып тастауға немесе жақын қалаңды таңдауға тырысыңыз.', // TODO: verify with native speaker
                'cn'          => '没有符合筛选条件的结果。请尝试清除日期或选择附近城市。', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Empty state body when filters are active',
            ],
            [
                'key'         => 'public.empty_body_no_cargo',
                'ru'          => 'Сейчас нет доступных грузов. Зарегистрируйтесь — мы уведомим вас, когда появятся новые.',
                'kz'          => 'Қазір қолжетімді жүктер жоқ. Тіркеліңіз — жаңалары пайда болған кезде хабарлаймыз.', // TODO: verify with native speaker
                'cn'          => '目前没有可用货物。注册后，有新货物时我们会通知您。', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Empty state body when no filters are active',
            ],

            // ─── Public landing page — footer ─────────────────────────────────
            [
                'key'         => 'public.footer_company_name',
                'ru'          => 'Silk Way',
                'kz'          => 'Silk Way',
                'cn'          => 'Silk Way',
                'group'       => 'public',
                'description' => 'Company name in footer',
            ],
            [
                'key'         => 'public.footer_company_tagline',
                'ru'          => 'Платформа для грузоперевозок в Центральной Азии',
                'kz'          => 'Орталық Азияда жүк тасымалдау платформасы', // TODO: verify with native speaker
                'cn'          => '中亚货运平台', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Short company tagline in footer',
            ],
            [
                'key'         => 'public.footer_col_product',
                'ru'          => 'Продукт',
                'kz'          => 'Өнім', // TODO: verify with native speaker
                'cn'          => '产品', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Footer column heading — product links',
            ],
            [
                'key'         => 'public.footer_col_drivers',
                'ru'          => 'Водителям',
                'kz'          => 'Жүргізушілерге', // TODO: verify with native speaker
                'cn'          => '司机专区', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Footer column heading — driver links',
            ],
            [
                'key'         => 'public.footer_col_legal',
                'ru'          => 'Документы',
                'kz'          => 'Құжаттар', // TODO: verify with native speaker
                'cn'          => '法律文件', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Footer column heading — legal links',
            ],
            [
                'key'         => 'public.footer_link_browse',
                'ru'          => 'Просмотр грузов',
                'kz'          => 'Жүктерді шолу', // TODO: verify with native speaker
                'cn'          => '浏览货物', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Footer link — browse cargo page',
            ],
            [
                'key'         => 'public.footer_link_register',
                'ru'          => 'Регистрация',
                'kz'          => 'Тіркелу', // TODO: verify with native speaker
                'cn'          => '注册', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Footer link — register',
            ],
            [
                'key'         => 'public.footer_link_login',
                'ru'          => 'Войти',
                'kz'          => 'Кіру', // TODO: verify with native speaker
                'cn'          => '登录', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Footer link — login',
            ],
            [
                'key'         => 'public.footer_link_how',
                'ru'          => 'Как это работает',
                'kz'          => 'Бұл қалай жұмыс істейді', // TODO: verify with native speaker
                'cn'          => '如何运作', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Footer link — how it works anchor',
            ],
            [
                'key'         => 'public.footer_link_privacy',
                'ru'          => 'Политика конфиденциальности',
                'kz'          => 'Құпиялылық саясаты', // TODO: verify with native speaker
                'cn'          => '隐私政策', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Footer link — privacy policy placeholder',
            ],
            [
                'key'         => 'public.footer_link_terms',
                'ru'          => 'Условия использования',
                'kz'          => 'Пайдалану шарттары', // TODO: verify with native speaker
                'cn'          => '使用条款', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Footer link — terms of service placeholder',
            ],
            [
                'key'         => 'public.footer_copyright',
                'ru'          => '© :year Silk Way. Все права защищены.',
                'kz'          => '© :year Silk Way. Барлық құқықтар қорғалған.', // TODO: verify with native speaker
                'cn'          => '© :year Silk Way. 保留所有权利。', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Footer copyright line — :year is replaced at render time',
            ],

            // ─── Public detail page — CTA ─────────────────────────────────────
            [
                'key'         => 'public.detail_cta_label',
                'ru'          => 'Войдите, чтобы подать заявку',
                'kz'          => 'Өтінім беру үшін кіріңіз', // TODO: verify with native speaker
                'cn'          => '登录后申请', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Sticky bottom CTA text on public detail page (replaces duplicate in-flow banner)',
            ],
            [
                'key'         => 'public.detail_back',
                'ru'          => 'Все грузы',
                'kz'          => 'Барлық жүктер', // TODO: verify with native speaker
                'cn'          => '所有货物', // TODO: verify with native speaker
                'group'       => 'public',
                'description' => 'Back link text on public detail page top bar',
            ],

            // ─── CMR UI copy ──────────────────────────────────────────────────
            // Labels — section headings and field labels
            [
                'key'         => 'cmr.label_section_title',
                'ru'          => 'CMR — подтверждение доставки',
                'kz'          => 'CMR — жеткізуді растау', // TODO: verify with native speaker
                'cn'          => 'CMR —交货确认', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Driver CMR section heading on application detail page',
            ],
            [
                'key'         => 'cmr.label_review_section_title',
                'ru'          => 'Проверка CMR',
                'kz'          => 'CMR тексеру', // TODO: verify with native speaker
                'cn'          => 'CMR 审核', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Reviewer (WE/admin) CMR section heading on application detail page',
            ],
            [
                'key'         => 'cmr.label_helper_text',
                'ru'          => 'Загрузите фото или скан подписанного CMR для подтверждения доставки.',
                'kz'          => 'Жеткізуді растау үшін қол қойылған CMR-дің фотосын немесе сканерін жүктеңіз.', // TODO: verify with native speaker
                'cn'          => '请上传已签署CMR的照片或扫描件以确认交货。', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Helper text shown in the driver CMR upload form (not_uploaded state)',
            ],
            [
                'key'         => 'cmr.label_file_types',
                'ru'          => 'Фото (JPG, PNG) или PDF',
                'kz'          => 'Сурет (JPG, PNG) немесе PDF', // TODO: verify with native speaker
                'cn'          => '照片 (JPG, PNG) 或 PDF', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Accepted file types hint inside CMR upload drop zone',
            ],
            [
                'key'         => 'cmr.label_max_size',
                'ru'          => 'Максимальный размер: 10 МБ',
                'kz'          => 'Максималды өлшем: 10 МБ', // TODO: verify with native speaker
                'cn'          => '最大文件大小：10MB', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Max file size hint inside CMR upload drop zone',
            ],
            [
                'key'         => 'cmr.label_confirmed_by',
                'ru'          => 'Подтверждено',
                'kz'          => 'Расталды', // TODO: verify with native speaker
                'cn'          => '已确认', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Prefix before name of user who confirmed the CMR',
            ],
            [
                'key'         => 'cmr.label_locked',
                'ru'          => 'CMR подтверждён и заблокирован для изменений',
                'kz'          => 'CMR расталған және өзгертуге бұғатталған', // TODO: verify with native speaker
                'cn'          => 'CMR已确认，不可更改', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Lock notice shown to driver when CMR is in confirmed state',
            ],
            [
                'key'         => 'cmr.label_previous_file',
                'ru'          => 'Ранее загруженный файл',
                'kz'          => 'Бұрын жүктелген файл', // TODO: verify with native speaker
                'cn'          => '之前上传的文件', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Caption above old file preview when CMR is in rejected state (driver view)',
            ],
            [
                'key'         => 'cmr.label_reupload_prompt',
                'ru'          => 'Загрузите исправленный CMR:',
                'kz'          => 'Түзетілген CMR жүктеңіз:', // TODO: verify with native speaker
                'cn'          => '请上传修正后的CMR：', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Prompt above re-upload form when CMR is in rejected state (driver view)',
            ],
            [
                'key'         => 'cmr.label_rejection_reason',
                'ru'          => 'Причина отклонения',
                'kz'          => 'Қабылдамау себебі', // TODO: verify with native speaker
                'cn'          => '拒绝原因', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Label for rejection reason textarea in reviewer reject form',
            ],
            [
                'key'         => 'cmr.label_rejection_reason_placeholder',
                'ru'          => 'Опишите, почему CMR не может быть принят...',
                'kz'          => 'CMR неліктен қабылданбайтынын сипаттаңыз...', // TODO: verify with native speaker
                'cn'          => '请说明CMR无法被接受的原因...', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Placeholder for rejection reason textarea',
            ],

            // Actions — button labels
            [
                'key'         => 'cmr.action_deliver',
                'ru'          => 'Доставить груз',
                'kz'          => 'Жүкті жеткізу', // TODO: verify with native speaker
                'cn'          => '确认交货', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'CTA button in my-cargo list and application detail when cmr_status = not_uploaded — opens the CMR upload flow',
            ],
            [
                'key'         => 'cmr.action_upload',
                'ru'          => 'Загрузить CMR',
                'kz'          => 'CMR жүктеу', // TODO: verify with native speaker
                'cn'          => '上传CMR', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Submit button inside the CMR file upload form',
            ],
            [
                'key'         => 'cmr.action_open',
                'ru'          => 'Открыть файл',
                'kz'          => 'Файлды ашу', // TODO: verify with native speaker
                'cn'          => '打开文件', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Label on PDF preview card — opens file in new tab',
            ],
            [
                'key'         => 'cmr.action_delete_reupload',
                'ru'          => 'Удалить и загрузить заново',
                'kz'          => 'Жою және қайта жүктеу', // TODO: verify with native speaker
                'cn'          => '删除并重新上传', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Delete + re-upload button shown to driver when cmr_status = pending_review',
            ],
            [
                'key'         => 'cmr.action_delete_confirm',
                'ru'          => 'Удалить загруженный CMR?',
                'kz'          => 'Жүктелген CMR жойылсын ба?', // TODO: verify with native speaker
                'cn'          => '确定删除已上传的CMR吗？', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Browser confirm dialog when driver deletes their uploaded CMR',
            ],
            [
                'key'         => 'cmr.action_confirm',
                'ru'          => 'Подтвердить CMR',
                'kz'          => 'CMR растау', // TODO: verify with native speaker
                'cn'          => '确认CMR', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Primary confirm button for reviewer on application detail (pending_review state)',
            ],
            [
                'key'         => 'cmr.action_confirm_dialog',
                'ru'          => 'Подтвердить CMR? После подтверждения груз будет помечен как доставлен.',
                'kz'          => 'CMR растайсыз ба? Растаудан кейін жүк жеткізілген деп белгіленеді.', // TODO: verify with native speaker
                'cn'          => '确认CMR？确认后货物将被标记为已交付。', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Browser confirm dialog shown to reviewer before confirming CMR',
            ],
            [
                'key'         => 'cmr.action_reject',
                'ru'          => 'Отклонить CMR',
                'kz'          => 'CMR қабылдамау', // TODO: verify with native speaker
                'cn'          => '拒绝CMR', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Ghost button that toggles the rejection reason textarea for reviewer',
            ],
            [
                'key'         => 'cmr.action_reject_submit',
                'ru'          => 'Отклонить',
                'kz'          => 'Қабылдамау', // TODO: verify with native speaker
                'cn'          => '拒绝', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Final submit button inside the rejection form',
            ],

            // Banners — status headline copy
            [
                'key'         => 'cmr.banner_pending_title',
                'ru'          => 'CMR загружен и отправлен на проверку',
                'kz'          => 'CMR жүктелді және тексеруге жіберілді', // TODO: verify with native speaker
                'cn'          => 'CMR已上传，待审核', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Amber banner headline shown to driver when cmr_status = pending_review',
            ],
            [
                'key'         => 'cmr.banner_pending_reviewer_title',
                'ru'          => 'Водитель загрузил CMR — требуется проверка',
                'kz'          => 'Жүргізуші CMR жүктеді — тексеру қажет', // TODO: verify with native speaker
                'cn'          => '司机已上传CMR — 需要审核', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Amber banner headline shown to reviewer (WE/admin) when cmr_status = pending_review',
            ],
            [
                'key'         => 'cmr.banner_confirmed_title',
                'ru'          => 'CMR подтверждён',
                'kz'          => 'CMR расталды', // TODO: verify with native speaker
                'cn'          => 'CMR已确认', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Emerald banner headline when cmr_status = confirmed (shown to driver and reviewer)',
            ],
            [
                'key'         => 'cmr.banner_rejected_title',
                'ru'          => 'CMR отклонён',
                'kz'          => 'CMR қабылданбады', // TODO: verify with native speaker
                'cn'          => 'CMR已被拒绝', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Rose banner headline when cmr_status = rejected (shown to driver and reviewer)',
            ],

            // Inline badges — shown in the applications list
            [
                'key'         => 'cmr.badge_pending',
                'ru'          => 'CMR на проверке',
                'kz'          => 'CMR тексерілуде', // TODO: verify with native speaker
                'cn'          => 'CMR审核中', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Amber badge shown next to driver name in applications list when cmr_status = pending_review',
            ],
            [
                'key'         => 'cmr.badge_rejected',
                'ru'          => 'CMR отклонён',
                'kz'          => 'CMR қабылданбады', // TODO: verify with native speaker
                'cn'          => 'CMR已拒绝', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Rose badge/label shown in my-cargo list when cmr_status = rejected',
            ],

            // CMR flow — backend status labels, flash messages and error strings
            [
                'key'         => 'cmr.status_not_uploaded',
                'ru'          => 'CMR не загружен',
                'kz'          => 'CMR жүктелмеген', // TODO: verify with native speaker
                'cn'          => 'CMR未上传', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Machine-readable CMR status label: not_uploaded',
            ],
            [
                'key'         => 'cmr.status_pending_review',
                'ru'          => 'Ожидает проверки',
                'kz'          => 'Тексеруді күтуде', // TODO: verify with native speaker
                'cn'          => '待审核', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Machine-readable CMR status label: pending_review',
            ],
            [
                'key'         => 'cmr.status_confirmed',
                'ru'          => 'Подтверждён',
                'kz'          => 'Расталды', // TODO: verify with native speaker
                'cn'          => '已确认', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Machine-readable CMR status label: confirmed',
            ],
            [
                'key'         => 'cmr.status_rejected',
                'ru'          => 'Отклонён',
                'kz'          => 'Қабылданбады', // TODO: verify with native speaker
                'cn'          => '已拒绝', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Machine-readable CMR status label: rejected',
            ],
            [
                'key'         => 'cmr.upload_success',
                'ru'          => 'CMR успешно загружен и отправлен на проверку.',
                'kz'          => 'CMR сәтті жүктелді және тексеруге жіберілді.', // TODO: verify with native speaker
                'cn'          => 'CMR已成功上传，等待审核。', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Flash success shown to driver after CMR upload',
            ],
            [
                'key'         => 'cmr.confirmed_success',
                'ru'          => 'CMR подтверждён. Груз переведён в статус «Доставлен».',
                'kz'          => 'CMR расталды. Жүк «Жеткізілді» мәртебесіне ауысты.', // TODO: verify with native speaker
                'cn'          => 'CMR已确认。货物状态已更新为"已送达"。', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Flash success shown to reviewer after confirming a CMR',
            ],
            [
                'key'         => 'cmr.rejected_success',
                'ru'          => 'CMR отклонён. Водитель может загрузить новый файл.',
                'kz'          => 'CMR қабылданбады. Жүргізуші жаңа файлды жүктей алады.', // TODO: verify with native speaker
                'cn'          => 'CMR已拒绝。司机可重新上传文件。', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Flash success shown to reviewer after rejecting a CMR',
            ],
            [
                'key'         => 'cmr.deleted_success',
                'ru'          => 'CMR-файл удалён.',
                'kz'          => 'CMR файлы жойылды.', // TODO: verify with native speaker
                'cn'          => 'CMR文件已删除。', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Flash success shown to driver after deleting their uploaded CMR',
            ],
            [
                'key'         => 'cmr.invalid_state',
                'ru'          => 'Действие невозможно в текущем состоянии CMR.',
                'kz'          => 'CMR ағымдағы күйінде бұл әрекет мүмкін емес.', // TODO: verify with native speaker
                'cn'          => '当前CMR状态下无法执行此操作。', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Error when a CMR state-transition is attempted at the wrong step',
            ],
            [
                'key'         => 'cmr.already_confirmed',
                'ru'          => 'CMR уже подтверждён и не может быть изменён.',
                'kz'          => 'CMR расталған және өзгертілуі мүмкін емес.', // TODO: verify with native speaker
                'cn'          => 'CMR已确认，无法修改。', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Error when driver tries to delete or re-upload a confirmed CMR',
            ],
            [
                'key'         => 'cmr.validation_file_required',
                'ru'          => 'Пожалуйста, выберите файл CMR для загрузки.',
                'kz'          => 'CMR файлын жүктеу үшін таңдаңыз.', // TODO: verify with native speaker
                'cn'          => '请选择要上传的CMR文件。', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Validation error when cmr_file is absent from the upload request',
            ],
            [
                'key'         => 'cmr.validation_reason_required',
                'ru'          => 'Укажите причину отклонения CMR.',
                'kz'          => 'CMR қабылданбау себебін көрсетіңіз.', // TODO: verify with native speaker
                'cn'          => '请填写拒绝CMR的原因。', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Validation error when rejection_reason is absent from the reject request',
            ],
            [
                'key'         => 'cmr.access_denied',
                'ru'          => 'У вас нет прав для выполнения этого действия с CMR.',
                'kz'          => 'Бұл CMR әрекетін орындауға рұқсатыңыз жоқ.', // TODO: verify with native speaker
                'cn'          => '您没有权限执行此CMR操作。', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Generic CMR authorisation denial message',
            ],

            // Driver registration UI copy
            [
                'key'         => 'driver_reg.title',
                'ru'          => 'Регистрация водителя',
                'kz'          => 'Жүргізушіні тіркеу', // TODO: verify with native speaker
                'cn'          => '司机注册', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => 'Page title for driver WhatsApp OTP registration',
            ],
            [
                'key'         => 'driver_reg.subtitle_step1',
                'ru'          => 'Регистрация через WhatsApp — быстро и безопасно',
                'kz'          => 'WhatsApp арқылы тіркелу — жылдам және қауіпсіз', // TODO: verify with native speaker
                'cn'          => '通过WhatsApp注册 — 快速安全', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => 'Subtitle shown under logo on step 1 (enter name + phone)',
            ],
            [
                'key'         => 'driver_reg.subtitle_step2',
                'ru'          => 'Введите код из WhatsApp и задайте пароль',
                'kz'          => 'WhatsApp кодын енгізіп, құпия сөз орнатыңыз', // TODO: verify with native speaker
                'cn'          => '输入WhatsApp验证码并设置密码', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => 'Subtitle shown under logo on step 2 (enter OTP + password)',
            ],
            [
                'key'         => 'driver_reg.phone_hint',
                'ru'          => 'Код придёт в WhatsApp на этот номер. Формат: +7 700 123 4567',
                'kz'          => 'Код осы нөмірге WhatsApp-та келеді. Формат: +7 700 123 4567', // TODO: verify with native speaker
                'cn'          => '验证码将通过WhatsApp发送到此号码。格式：+7 700 123 4567', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => 'Helper text below the phone input on step 1',
            ],
            [
                'key'         => 'driver_reg.request_button',
                'ru'          => 'Получить код',
                'kz'          => 'Код алу', // TODO: verify with native speaker
                'cn'          => '获取验证码', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => 'Submit button label on step 1',
            ],
            [
                'key'         => 'driver_reg.code_sent_to',
                'ru'          => 'Код отправлен на',
                'kz'          => 'Код жіберілді:', // TODO: verify with native speaker
                'cn'          => '验证码已发送至', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => 'Prefix in the info banner on step 2, followed by the phone number',
            ],
            [
                'key'         => 'driver_reg.change_number',
                'ru'          => 'Изменить номер',
                'kz'          => 'Нөмірді өзгерту', // TODO: verify with native speaker
                'cn'          => '更改号码', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => 'Link in the step 2 info banner that resets the session back to step 1',
            ],
            [
                'key'         => 'driver_reg.code_label',
                'ru'          => 'Код из WhatsApp',
                'kz'          => 'WhatsApp коды', // TODO: verify with native speaker
                'cn'          => 'WhatsApp验证码', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => 'Label for the 6-digit OTP code input on step 2',
            ],
            [
                'key'         => 'driver_reg.code_hint',
                'ru'          => '6 цифр, действителен 10 минут',
                'kz'          => '6 сан, 10 минут ішінде жарамды', // TODO: verify with native speaker
                'cn'          => '6位数字，有效期10分钟', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => 'Helper text below the OTP input on step 2',
            ],
            [
                'key'         => 'driver_reg.verify_button',
                'ru'          => 'Завершить регистрацию',
                'kz'          => 'Тіркеуді аяқтау', // TODO: verify with native speaker
                'cn'          => '完成注册', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => 'Submit button label on step 2',
            ],
            [
                'key'         => 'driver_reg.resend_prompt',
                'ru'          => 'Не получили код?',
                'kz'          => 'Код келген жоқ па?', // TODO: verify with native speaker
                'cn'          => '没有收到验证码？', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => 'Prompt text above the resend button on step 2',
            ],
            [
                'key'         => 'driver_reg.resend_available_in',
                'ru'          => 'Повторная отправка через',
                'kz'          => 'Қайта жіберу арқылы', // TODO: verify with native speaker
                'cn'          => '重新发送将在', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => 'Countdown prefix text; the seconds value is appended inline via Alpine x-text',
            ],
            [
                'key'         => 'driver_reg.resend_button',
                'ru'          => 'Отправить код повторно',
                'kz'          => 'Кодты қайта жіберу', // TODO: verify with native speaker
                'cn'          => '重新发送验证码', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => 'Resend button label; visible after 60-second countdown expires',
            ],
            [
                'key'         => 'driver_reg.success',
                'ru'          => 'Регистрация успешно завершена. Войдите в аккаунт.',
                'kz'          => 'Тіркеу сәтті аяқталды. Жүйеге кіріңіз.', // TODO: verify with native speaker
                'cn'          => '注册成功。请登录。', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => 'Flash success message shown on the login page after successful driver registration',
            ],
            [
                'key'         => 'auth.phone',
                'ru'          => 'Номер телефона',
                'kz'          => 'Телефон нөмірі', // TODO: verify with native speaker
                'cn'          => '电话号码', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Phone number field label used on driver registration step 1',
            ],

            // ── Driver WhatsApp OTP registration — additional keys ─────────────

            [
                'key'         => 'driver_reg.subtitle',
                'ru'          => 'Введите ваш номер телефона — мы отправим код подтверждения в WhatsApp.',
                'kz'          => 'Телефон нөмірін енгізіңіз — растау кодын WhatsApp-қа жібереміз.', // TODO: verify with native speaker
                'cn'          => '输入手机号码，我们将通过WhatsApp发送验证码。', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => 'Subtitle / instruction shown on the first step of driver registration',
            ],
            [
                'key'         => 'driver_reg.field_name',
                'ru'          => 'Ваше имя',
                'kz'          => 'Атыңыз', // TODO: verify with native speaker
                'cn'          => '您的姓名', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => 'Label for the name input field in driver registration',
            ],
            [
                'key'         => 'driver_reg.field_phone',
                'ru'          => 'Номер телефона (WhatsApp)',
                'kz'          => 'Телефон нөмірі (WhatsApp)', // TODO: verify with native speaker
                'cn'          => '手机号码 (WhatsApp)', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => 'Label for the phone input field in driver registration step 1',
            ],
            [
                'key'         => 'driver_reg.field_code',
                'ru'          => 'Код из WhatsApp',
                'kz'          => 'WhatsApp коды', // TODO: verify with native speaker
                'cn'          => 'WhatsApp验证码', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => 'Label for the OTP code input in step 2',
            ],
            [
                'key'         => 'driver_reg.field_password',
                'ru'          => 'Пароль',
                'kz'          => 'Құпия сөз', // TODO: verify with native speaker
                'cn'          => '密码', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => 'Label for the password field in driver registration step 2',
            ],
            [
                'key'         => 'driver_reg.field_password_confirmation',
                'ru'          => 'Повторите пароль',
                'kz'          => 'Құпия сөзді қайталаңыз', // TODO: verify with native speaker
                'cn'          => '确认密码', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => 'Label for password_confirmation in step 2',
            ],
            [
                'key'         => 'driver_reg.code_sent',
                'ru'          => 'Код подтверждения отправлен в WhatsApp. Действителен 10 минут.',
                'kz'          => 'Растау коды WhatsApp-қа жіберілді. 10 минут бойы жарамды.', // TODO: verify with native speaker
                'cn'          => '验证码已发送至WhatsApp，有效期10分钟。', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => 'Success notice returned after OTP is sent (step 1 / API message field)',
            ],
            [
                'key'         => 'driver_reg.code_resent',
                'ru'          => 'Новый код отправлен в WhatsApp.',
                'kz'          => 'Жаңа код WhatsApp-қа жіберілді.', // TODO: verify with native speaker
                'cn'          => '新验证码已发送至WhatsApp。', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => 'Success notice returned after OTP is resent (resend step)',
            ],
            [
                'key'         => 'driver_reg.error_phone_taken',
                'ru'          => 'Этот номер телефона уже зарегистрирован.',
                'kz'          => 'Бұл телефон нөмірі бұрын тіркелген.', // TODO: verify with native speaker
                'cn'          => '该手机号码已注册。', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => '409 message when the phone already belongs to a User record',
            ],
            [
                'key'         => 'driver_reg.error_phone_not_whatsapp',
                'ru'          => 'Номер не найден в WhatsApp. Проверьте номер и попробуйте снова.',
                'kz'          => 'Нөмір WhatsApp-та табылмады. Нөмірді тексеріп, қайталап көріңіз.', // TODO: verify with native speaker
                'cn'          => '该号码未在WhatsApp注册，请检查号码后重试。', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => '422 message when WAHA check-exists returns numberExists: false',
            ],
            [
                'key'         => 'driver_reg.error_code_expired',
                'ru'          => 'Код истёк. Запросите новый.',
                'kz'          => 'Код мерзімі өтті. Жаңасын сұраңыз.', // TODO: verify with native speaker
                'cn'          => '验证码已过期，请重新获取。', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => '410 message when the OTP expires_at is in the past',
            ],
            [
                'key'         => 'driver_reg.error_code_wrong',
                'ru'          => 'Неверный код. Проверьте и попробуйте снова.',
                'kz'          => 'Код қате. Тексеріп, қайталап көріңіз.', // TODO: verify with native speaker
                'cn'          => '验证码不正确，请检查后重试。', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => '422 message on wrong OTP (increments attempts counter)',
            ],
            [
                'key'         => 'driver_reg.error_too_many_attempts',
                'ru'          => 'Превышен лимит попыток. Запросите новый код.',
                'kz'          => 'Әрекет саны асып кетті. Жаңа код сұраңыз.', // TODO: verify with native speaker
                'cn'          => '尝试次数过多，请重新获取验证码。', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => '429 message when attempt counter reaches MAX_ATTEMPTS (5)',
            ],
            [
                'key'         => 'driver_reg.error_service_unavailable',
                'ru'          => 'Сервис временно недоступен. Попробуйте позже.',
                'kz'          => 'Қызмет уақытша қолжетімсіз. Кейінірек қайталаңыз.', // TODO: verify with native speaker
                'cn'          => '服务暂时不可用，请稍后重试。', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => '503 message when WAHA session is not WORKING — must never expose internal reason',
            ],
            [
                'key'         => 'driver_reg.error_rate_limit',
                'ru'          => 'Слишком много запросов. Подождите 60 секунд перед повторной отправкой.',
                'kz'          => 'Өте көп сұраулар. Қайта жіберу алдында 60 секунд күтіңіз.', // TODO: verify with native speaker
                'cn'          => '请求过于频繁，请等待60秒后重试。', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => '429 message for the 60 s per-phone resend throttle',
            ],
            [
                'key'         => 'driver_reg.wa_message',
                'ru'          => 'Silk Way: ваш код подтверждения :code. Действителен 10 минут.',
                'kz'          => 'Silk Way: растау кодыңыз :code. 10 минут бойы жарамды.', // TODO: verify with native speaker
                'cn'          => 'Silk Way: 您的验证码是 :code，有效期10分钟。', // TODO: verify with native speaker
                'group'       => 'driver_reg',
                'description' => 'WhatsApp OTP message template; :code is replaced with the actual 6-digit code at runtime',
            ],

            // ── Driver WhatsApp OTP Login ──────────────────────────────────────────
            [
                'key'         => 'driver_login.title',
                'ru'          => 'Вход через WhatsApp',
                'kz'          => 'WhatsApp арқылы кіру', // TODO: verify with native speaker
                'cn'          => '通过WhatsApp登录', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => 'Page title and card heading for driver WhatsApp OTP login',
            ],
            [
                'key'         => 'driver_login.subtitle_step1',
                'ru'          => 'Войдите в аккаунт водителя через WhatsApp',
                'kz'          => 'WhatsApp арқылы жүргізуші аккаунтына кіріңіз', // TODO: verify with native speaker
                'cn'          => '通过WhatsApp登录司机账户', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => 'Subtitle under logo on step 1 (enter phone)',
            ],
            [
                'key'         => 'driver_login.subtitle_step2',
                'ru'          => 'Введите код из WhatsApp для входа',
                'kz'          => 'Кіру үшін WhatsApp кодын енгізіңіз', // TODO: verify with native speaker
                'cn'          => '输入WhatsApp验证码以登录', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => 'Subtitle under logo on step 2 (enter OTP)',
            ],
            [
                'key'         => 'driver_login.phone_hint',
                'ru'          => 'Код придёт в WhatsApp на этот номер. Формат: +7 700 123 4567',
                'kz'          => 'Код осы нөмірге WhatsApp-та келеді. Формат: +7 700 123 4567', // TODO: verify with native speaker
                'cn'          => '验证码将通过WhatsApp发送到此号码。格式：+7 700 123 4567', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => 'Helper text below the phone input on step 1',
            ],
            [
                'key'         => 'driver_login.request_button',
                'ru'          => 'Отправить код',
                'kz'          => 'Код жіберу', // TODO: verify with native speaker
                'cn'          => '发送验证码', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => 'Submit button label on step 1',
            ],
            [
                'key'         => 'driver_login.code_sent_to',
                'ru'          => 'Код отправлен на',
                'kz'          => 'Код жіберілді:', // TODO: verify with native speaker
                'cn'          => '验证码已发送至', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => 'Prefix in the info banner on step 2, followed by the phone number',
            ],
            [
                'key'         => 'driver_login.change_number',
                'ru'          => 'Изменить номер',
                'kz'          => 'Нөмірді өзгерту', // TODO: verify with native speaker
                'cn'          => '更改号码', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => 'Link in the step 2 banner that resets the session back to step 1',
            ],
            [
                'key'         => 'driver_login.code_label',
                'ru'          => 'Код из WhatsApp',
                'kz'          => 'WhatsApp коды', // TODO: verify with native speaker
                'cn'          => 'WhatsApp验证码', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => 'Label for the 6-digit OTP input on step 2',
            ],
            [
                'key'         => 'driver_login.code_hint',
                'ru'          => '6-значный код из сообщения WhatsApp',
                'kz'          => 'WhatsApp хабарламасындағы 6 таңбалы код', // TODO: verify with native speaker
                'cn'          => 'WhatsApp消息中的6位验证码', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => 'Helper text below the OTP code input on step 2',
            ],
            [
                'key'         => 'driver_login.verify_button',
                'ru'          => 'Войти',
                'kz'          => 'Кіру', // TODO: verify with native speaker
                'cn'          => '登录', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => 'Submit button label on step 2 (verify OTP and log in)',
            ],
            [
                'key'         => 'driver_login.resend_prompt',
                'ru'          => 'Не получили код?',
                'kz'          => 'Код келмеді ме?', // TODO: verify with native speaker
                'cn'          => '没有收到验证码？', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => 'Prompt text above the resend countdown/button on step 2',
            ],
            [
                'key'         => 'driver_login.resend_available_in',
                'ru'          => 'Повторная отправка через',
                'kz'          => 'Қайта жіберу', // TODO: verify with native speaker
                'cn'          => '重新发送倒计时', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => 'Countdown label prefix; followed by seconds count and "сек."',
            ],
            [
                'key'         => 'driver_login.resend_button',
                'ru'          => 'Отправить код повторно',
                'kz'          => 'Кодты қайта жіберу', // TODO: verify with native speaker
                'cn'          => '重新发送验证码', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => 'Resend button label that appears after 60-second countdown',
            ],
            [
                'key'         => 'driver_login.no_account',
                'ru'          => 'Нет аккаунта?',
                'kz'          => 'Аккаунт жоқ па?', // TODO: verify with native speaker
                'cn'          => '没有账户？', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => 'Footer prompt on driver login page for new users',
            ],
            [
                'key'         => 'driver_login.register_link',
                'ru'          => 'Зарегистрироваться через WhatsApp',
                'kz'          => 'WhatsApp арқылы тіркелу', // TODO: verify with native speaker
                'cn'          => '通过WhatsApp注册', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => 'Link text pointing to driver registration from the driver login footer',
            ],
            [
                'key'         => 'driver_login.login_page_prompt',
                'ru'          => 'Водитель?',
                'kz'          => 'Жүргізуші ме?', // TODO: verify with native speaker
                'cn'          => '是司机？', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => 'Prompt text on the main email/password login page pointing drivers to WhatsApp login',
            ],
            [
                'key'         => 'driver_login.login_page_link',
                'ru'          => 'Войти через WhatsApp',
                'kz'          => 'WhatsApp арқылы кіру', // TODO: verify with native speaker
                'cn'          => '通过WhatsApp登录', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => 'Link text on the main login page directing drivers to WhatsApp OTP login',
            ],
            [
                'key'         => 'driver_login.already_have_account',
                'ru'          => 'Уже есть аккаунт?',
                'kz'          => 'Аккаунт бар ма?', // TODO: verify with native speaker
                'cn'          => '已有账户？', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => 'Footer prompt on driver register page for existing drivers',
            ],
            [
                'key'         => 'driver_login.login_whatsapp_link',
                'ru'          => 'Войти через WhatsApp',
                'kz'          => 'WhatsApp арқылы кіру', // TODO: verify with native speaker
                'cn'          => '通过WhatsApp登录', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => 'Link text on driver register page pointing to WhatsApp OTP login',
            ],

            // ── Driver WhatsApp login — service / API keys ─────────────────────

            [
                'key'         => 'driver_login.field_phone',
                'ru'          => 'Номер телефона (WhatsApp)',
                'kz'          => 'Телефон нөмірі (WhatsApp)', // TODO: verify with native speaker
                'cn'          => '手机号码 (WhatsApp)', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => 'Label for the phone input field in driver login step 1',
            ],
            [
                'key'         => 'driver_login.field_code',
                'ru'          => 'Код из WhatsApp',
                'kz'          => 'WhatsApp коды', // TODO: verify with native speaker
                'cn'          => 'WhatsApp验证码', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => 'Label for the OTP code input in driver login step 2',
            ],
            [
                'key'         => 'driver_login.code_sent',
                'ru'          => 'Код для входа отправлен в WhatsApp. Действителен 10 минут.',
                'kz'          => 'Кіру коды WhatsApp-қа жіберілді. 10 минут бойы жарамды.', // TODO: verify with native speaker
                'cn'          => '登录验证码已发送至WhatsApp，有效期10分钟。', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => 'Success notice after OTP is sent on driver login step 1 (also API message field)',
            ],
            [
                'key'         => 'driver_login.code_resent',
                'ru'          => 'Новый код для входа отправлен в WhatsApp.',
                'kz'          => 'Жаңа кіру коды WhatsApp-қа жіберілді.', // TODO: verify with native speaker
                'cn'          => '新登录验证码已发送至WhatsApp。', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => 'Success notice after resend on driver login',
            ],
            [
                'key'         => 'driver_login.login_success',
                'ru'          => 'Вход выполнен успешно.',
                'kz'          => 'Сәтті кірдіңіз.', // TODO: verify with native speaker
                'cn'          => '登录成功。', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => 'Success message returned in the API verify response body',
            ],
            [
                'key'         => 'driver_login.error_not_found',
                'ru'          => 'Аккаунт с таким номером не найден. Зарегистрируйтесь.',
                'kz'          => 'Мұндай нөмірмен аккаунт табылмады. Тіркеліңіз.', // TODO: verify with native speaker
                'cn'          => '未找到该号码的账户，请先注册。', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => '404 message when no driver user matches the phone number',
            ],
            [
                'key'         => 'driver_login.error_not_approved',
                'ru'          => 'Регистрация завершена. Ожидайте подтверждения администратора — мы напишем в WhatsApp, когда аккаунт активируют.',
                'kz'          => 'Тіркелу аяқталды. Әкімшінің растауын күтіңіз — есептік жазба белсендірілгенде WhatsApp-та хабарлама жібереміз.', // TODO: verify with native speaker
                'cn'          => '注册已完成。请等待管理员确认 — 账户激活后我们将通过 WhatsApp 通知您。', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => '403 message after OTP verify when account is not yet admin-approved (covers both newly auto-registered and previously rejected accounts)',
            ],
            [
                'key'         => 'driver_login.error_phone_conflict',
                'ru'          => 'Этот номер уже зарегистрирован под другим типом аккаунта. Войдите через email/пароль.',
                'kz'          => 'Бұл нөмір басқа есептік жазба түрімен тіркелген. Email/құпия сөз арқылы кіріңіз.', // TODO: verify with native speaker
                'cn'          => '此号码已注册为其他类型账户。请使用邮箱/密码登录。', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => '409 — phone belongs to a non-driver account (admin / warehouse_employee)',
            ],
            [
                'key'         => 'driver_login.error_no_pending_code',
                'ru'          => 'Сначала запросите код.',
                'kz'          => 'Алдымен кодты сұраңыз.', // TODO: verify with native speaker
                'cn'          => '请先获取验证码。', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => '422 message when verify/resend called with no active phone_verifications row',
            ],
            [
                'key'         => 'driver_login.error_code_expired',
                'ru'          => 'Код истёк. Запросите новый.',
                'kz'          => 'Код мерзімі өтті. Жаңасын сұраңыз.', // TODO: verify with native speaker
                'cn'          => '验证码已过期，请重新获取。', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => '410 message when the OTP expires_at is in the past',
            ],
            [
                'key'         => 'driver_login.error_code_wrong',
                'ru'          => 'Неверный код. Проверьте и попробуйте снова.',
                'kz'          => 'Код қате. Тексеріп, қайталап көріңіз.', // TODO: verify with native speaker
                'cn'          => '验证码不正确，请检查后重试。', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => '422 message on wrong OTP — increments the attempts counter',
            ],
            [
                'key'         => 'driver_login.error_too_many_attempts',
                'ru'          => 'Превышен лимит попыток. Запросите новый код.',
                'kz'          => 'Әрекет саны асып кетті. Жаңа код сұраңыз.', // TODO: verify with native speaker
                'cn'          => '尝试次数过多，请重新获取验证码。', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => '429 message when attempt counter reaches MAX_ATTEMPTS (5)',
            ],
            [
                'key'         => 'driver_login.error_service_unavailable',
                'ru'          => 'Сервис временно недоступен. Попробуйте позже.',
                'kz'          => 'Қызмет уақытша қолжетімсіз. Кейінірек қайталаңыз.', // TODO: verify with native speaker
                'cn'          => '服务暂时不可用，请稍后重试。', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => '503 message when WAHA session is not WORKING',
            ],
            [
                'key'         => 'driver_login.error_rate_limit',
                'ru'          => 'Слишком много запросов. Подождите 60 секунд перед повторной отправкой.',
                'kz'          => 'Өте көп сұраулар. Қайта жіберу алдында 60 секунд күтіңіз.', // TODO: verify with native speaker
                'cn'          => '请求过于频繁，请等待60秒后重试。', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => '429 message for the 60 s per-phone resend throttle on driver login',
            ],
            [
                'key'         => 'driver_login.wa_message',
                'ru'          => 'Silk Way: код для входа :code. Действителен 10 минут. Не передавайте его никому.',
                'kz'          => 'Silk Way: кіру кодыңыз :code. 10 минут бойы жарамды. Ешкімге бермеңіз.', // TODO: verify with native speaker
                'cn'          => 'Silk Way: 您的登录验证码是 :code，有效期10分钟，请勿告知他人。', // TODO: verify with native speaker
                'group'       => 'driver_login',
                'description' => 'WhatsApp OTP message template for driver login; :code is replaced with the 6-digit code at runtime',
            ],

            // ── Unified register UI ────────────────────────────────────────────────
            [
                'key'         => 'auth.register_tab_driver_label',
                'ru'          => 'Я водитель',
                'kz'          => 'Мен жүргізуші', // TODO: verify with native speaker
                'cn'          => '我是司机', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Tab selector label for the driver (WhatsApp OTP) registration flow',
            ],
            [
                'key'         => 'auth.register_tab_driver_sub',
                'ru'          => 'Регистрация через WhatsApp-код',
                'kz'          => 'WhatsApp-код арқылы тіркелу', // TODO: verify with native speaker
                'cn'          => '通过 WhatsApp 验证码注册', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Tab selector subtext for the driver registration tab',
            ],
            [
                'key'         => 'auth.register_tab_warehouse_label',
                'ru'          => 'Складской сотрудник',
                'kz'          => 'Қойма қызметкері', // TODO: verify with native speaker
                'cn'          => '仓库员工', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Tab selector label for the warehouse employee (email/password) registration flow',
            ],
            [
                'key'         => 'auth.register_tab_warehouse_sub',
                'ru'          => 'Вход по email и паролю',
                'kz'          => 'Email және парольмен кіру', // TODO: verify with native speaker
                'cn'          => '通过邮箱和密码登录', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Tab selector subtext for the warehouse registration tab',
            ],
            [
                'key'         => 'auth.error_heading_generic',
                'ru'          => 'Не удалось зарегистрироваться',
                'kz'          => 'Тіркелу сәтсіз аяқталды', // TODO: verify with native speaker
                'cn'          => '注册失败', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Generic error banner heading shown on registration failure',
            ],
            [
                'key'         => 'auth.error_heading_wa_send',
                'ru'          => 'Не удалось отправить код',
                'kz'          => 'Код жіберілмеді', // TODO: verify with native speaker
                'cn'          => '发送验证码失败', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Error banner heading when driver step 1 code send fails',
            ],
            [
                'key'         => 'auth.error_heading_wa_verify',
                'ru'          => 'Неверный код',
                'kz'          => 'Код қате', // TODO: verify with native speaker
                'cn'          => '验证码不正确', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Error banner heading when driver step 2 OTP verification fails',
            ],
            [
                'key'         => 'auth.error_waha_down_title',
                'ru'          => 'Сервис временно недоступен',
                'kz'          => 'Қызмет уақытша қолжетімсіз', // TODO: verify with native speaker
                'cn'          => '服务暂时不可用', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Amber warning banner title when WAHA (WhatsApp) is unreachable (503)',
            ],
            [
                'key'         => 'auth.error_waha_down_body',
                'ru'          => 'Не удаётся подключиться к WhatsApp. Попробуйте через несколько минут. Если проблема не уходит, напишите администратору.',
                'kz'          => 'WhatsApp-қа қосылу мүмкін болмады. Бірнеше минуттан кейін қайталап көріңіз. Мәселе шешілмесе, әкімшіге хабарласыңыз.', // TODO: verify with native speaker
                'cn'          => '无法连接 WhatsApp，请稍后几分钟再试。如问题持续，请联系管理员。', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Amber warning banner body when WAHA is unreachable (503)',
            ],
            [
                'key'         => 'auth.error_rate_limit_title',
                'ru'          => 'Слишком много запросов',
                'kz'          => 'Өте көп сұраулар', // TODO: verify with native speaker
                'cn'          => '请求过于频繁', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Amber banner title shown on 429 rate limit response',
            ],
            [
                'key'         => 'auth.error_rate_limit_body',
                'ru'          => 'Подождите минуту перед повторной попыткой.',
                'kz'          => 'Қайталамас бұрын бір минут күтіңіз.', // TODO: verify with native speaker
                'cn'          => '请等待一分钟后再重试。', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Amber banner body shown on 429 rate limit response',
            ],
            [
                'key'         => 'auth.error_phone_taken_cta',
                'ru'          => 'Этот номер уже зарегистрирован. Войти?',
                'kz'          => 'Бұл нөмір тіркелген. Кіргіңіз бе?', // TODO: verify with native speaker
                'cn'          => '该号码已注册，是否登录？', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Inline call-to-action in the rose error banner when the phone number is already taken (409)',
            ],
            [
                'key'         => 'auth.submitting',
                'ru'          => 'Отправляем...',
                'kz'          => 'Жіберілуде...', // TODO: verify with native speaker
                'cn'          => '正在提交…', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Button label while warehouse registration form is submitting',
            ],
            [
                'key'         => 'auth.sending_code',
                'ru'          => 'Отправляем...',
                'kz'          => 'Жіберілуде...', // TODO: verify with native speaker
                'cn'          => '正在发送…', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Button label while WhatsApp OTP code send is in progress',
            ],
            [
                'key'         => 'auth.verifying',
                'ru'          => 'Проверяем...',
                'kz'          => 'Тексерілуде...', // TODO: verify with native speaker
                'cn'          => '正在验证…', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Button label while OTP verify request is in progress',
            ],
            [
                'key'         => 'auth.code_sent_flash',
                'ru'          => 'Код отправлен в WhatsApp',
                'kz'          => 'Код WhatsApp-қа жіберілді', // TODO: verify with native speaker
                'cn'          => '验证码已发送至 WhatsApp', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Emerald success flash banner shown after driver step 1 OTP is sent successfully',
            ],
            [
                'key'         => 'auth.expired_code_cta',
                'ru'          => 'Запросить новый код',
                'kz'          => 'Жаңа код сұрау', // TODO: verify with native speaker
                'cn'          => '重新获取验证码', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Button inside the expired-code error banner that triggers a resend request',
            ],
            [
                'key'         => 'auth.already_have_account',
                'ru'          => 'Уже есть аккаунт?',
                'kz'          => 'Тіркелгі бар ма?', // TODO: verify with native speaker
                'cn'          => '已有账户？', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Prompt text above the login link at the bottom of the register page',
            ],

            // ── Unified login UI ───────────────────────────────────────────────────
            [
                'key'         => 'auth.login_tab_driver_label',
                'ru'          => 'Я водитель',
                'kz'          => 'Мен жүргізушімін', // TODO: verify with native speaker
                'cn'          => '我是司机', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Tab selector label for the driver (WhatsApp OTP) login flow on the unified login page',
            ],
            [
                'key'         => 'auth.login_tab_driver_sub',
                'ru'          => 'Вход через WhatsApp-код',
                'kz'          => 'WhatsApp-код арқылы кіру', // TODO: verify with native speaker
                'cn'          => '通过 WhatsApp 验证码登录', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Tab selector subtext for the driver login tab on the unified login page',
            ],
            [
                'key'         => 'auth.login_tab_warehouse_label',
                'ru'          => 'Склад / Админ',
                'kz'          => 'Қойма / Әкімші', // TODO: verify with native speaker
                'cn'          => '仓库 / 管理员', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Tab selector label for the warehouse/admin (email+password) login flow on the unified login page',
            ],
            [
                'key'         => 'auth.login_tab_warehouse_sub',
                'ru'          => 'Вход по email и паролю',
                'kz'          => 'Email және парольмен кіру', // TODO: verify with native speaker
                'cn'          => '通过邮箱和密码登录', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Tab selector subtext for the warehouse/admin login tab on the unified login page',
            ],
            [
                'key'         => 'auth.login_error_heading_generic',
                'ru'          => 'Не удалось войти',
                'kz'          => 'Кіру сәтсіз аяқталды', // TODO: verify with native speaker
                'cn'          => '登录失败', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Generic error banner heading shown on login failure (warehouse flow or unclassified driver error)',
            ],
            [
                'key'         => 'auth.login_error_heading_wa_send',
                'ru'          => 'Не удалось отправить код',
                'kz'          => 'Код жіберілмеді', // TODO: verify with native speaker
                'cn'          => '发送验证码失败', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Error banner heading when driver login step 1 code send fails',
            ],
            [
                'key'         => 'auth.login_error_heading_wa_verify',
                'ru'          => 'Неверный или просроченный код',
                'kz'          => 'Код қате немесе мерзімі өткен', // TODO: verify with native speaker
                'cn'          => '验证码不正确或已过期', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Error banner heading when driver login step 2 OTP verification fails',
            ],
            [
                'key'         => 'auth.login_card_heading',
                'ru'          => 'Вход',
                'kz'          => 'Кіру', // TODO: verify with native speaker
                'cn'          => '登录', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Card heading on the unified login page when warehouse/admin tab is active',
            ],
            [
                'key'         => 'auth.login_card_heading_driver',
                'ru'          => 'Вход водителя',
                'kz'          => 'Жүргізуші кіруі', // TODO: verify with native speaker
                'cn'          => '司机登录', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Card heading on the unified login page when driver tab step 1 is active',
            ],
            [
                'key'         => 'auth.login_card_heading_driver_step2',
                'ru'          => 'Проверка кода',
                'kz'          => 'Кодты тексеру', // TODO: verify with native speaker
                'cn'          => '验证码确认', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Card heading and subtitle on the unified login page when driver tab step 2 is active',
            ],
            [
                'key'         => 'auth.login_submitting',
                'ru'          => 'Входим...',
                'kz'          => 'Кіруде...', // TODO: verify with native speaker
                'cn'          => '正在登录…', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Button label while the warehouse/admin login form is submitting',
            ],

            // ── Driver flow redirects ──────────────────────────────────────────────
            [
                'key'         => 'auth.driver_use_whatsapp_register',
                'ru'          => 'Водители регистрируются через WhatsApp. Откройте форму регистрации для водителей.',
                'kz'          => 'Жүргізушілер WhatsApp арқылы тіркеледі. Жүргізушілерге арналған тіркелу нысанын ашыңыз.', // TODO: verify with native speaker
                'cn'          => '司机通过WhatsApp注册。请打开司机注册表单。', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Error shown when a driver role is submitted to the legacy /register endpoint; redirects user to the WhatsApp registration flow',
            ],
            [
                'key'         => 'auth.driver_use_whatsapp_login',
                'ru'          => 'Водители входят через WhatsApp. Откройте форму входа для водителей.',
                'kz'          => 'Жүргізушілер WhatsApp арқылы кіреді. Жүргізушілерге арналған кіру нысанын ашыңыз.', // TODO: verify with native speaker
                'cn'          => '司机通过WhatsApp登录。请打开司机登录表单。', // TODO: verify with native speaker
                'group'       => 'auth',
                'description' => 'Error shown when a driver tries to log in via the legacy /login endpoint; redirects user to the WhatsApp login flow',
            ],

            // ── Profile UI copy ───────────────────────────────────────────────────
            [
                'key'         => 'profile.page_title',
                'ru'          => 'Профиль',
                'kz'          => 'Профиль', // TODO: verify with native speaker
                'cn'          => '个人资料', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Browser tab title and page heading for the profile page',
            ],
            [
                'key'         => 'profile.subtitle_driver_placeholder',
                'ru'          => 'Заполните своё имя — оно будет видно складским сотрудникам при подаче заявок.',
                'kz'          => 'Атыңызды толтырыңыз — ол қоймадағы қызметкерлерге өтініш беру кезінде көрінеді.', // TODO: verify with native speaker
                'cn'          => '请填写您的姓名——提交申请时仓库员工将看到它。', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Page subtitle shown to drivers whose name is still an auto-generated placeholder (starts with "Водитель ")',
            ],
            [
                'key'         => 'profile.subtitle_driver',
                'ru'          => 'Ваши данные.',
                'kz'          => 'Сіздің деректеріңіз.', // TODO: verify with native speaker
                'cn'          => '您的资料。', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Page subtitle shown to drivers who have already set a real name',
            ],
            [
                'key'         => 'profile.subtitle_staff',
                'ru'          => 'Управление аккаунтом.',
                'kz'          => 'Аккаунтты басқару.', // TODO: verify with native speaker
                'cn'          => '账号管理。', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Page subtitle shown to admins and warehouse employees',
            ],
            [
                'key'         => 'profile.role_admin',
                'ru'          => 'Администратор',
                'kz'          => 'Әкімші', // TODO: verify with native speaker
                'cn'          => '管理员', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Role badge label for admin users on the profile page',
            ],
            [
                'key'         => 'profile.role_warehouse',
                'ru'          => 'Складской сотрудник',
                'kz'          => 'Қойма қызметкері', // TODO: verify with native speaker
                'cn'          => '仓库员工', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Role badge label for warehouse employee users on the profile page',
            ],
            [
                'key'         => 'profile.role_driver',
                'ru'          => 'Водитель',
                'kz'          => 'Жүргізуші', // TODO: verify with native speaker
                'cn'          => '司机', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Role badge label for driver users on the profile page',
            ],
            [
                'key'         => 'profile.status_approved',
                'ru'          => 'Подтверждён администратором',
                'kz'          => 'Әкімші растады', // TODO: verify with native speaker
                'cn'          => '已由管理员确认', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Approval status pill shown on driver profile when approved = true',
            ],
            [
                'key'         => 'profile.status_pending',
                'ru'          => 'На проверке',
                'kz'          => 'Тексерілуде', // TODO: verify with native speaker
                'cn'          => '审核中', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Approval status pill shown on driver profile when approved = false',
            ],
            [
                'key'         => 'profile.phone_label',
                'ru'          => 'Телефон',
                'kz'          => 'Телефон', // TODO: verify with native speaker
                'cn'          => '电话', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Label above the locked phone chip on the driver profile page',
            ],
            [
                'key'         => 'profile.phone_locked_hint',
                'ru'          => 'Используется для входа через WhatsApp — изменить нельзя.',
                'kz'          => 'WhatsApp арқылы кіру үшін пайдаланылады — өзгертуге болмайды.', // TODO: verify with native speaker
                'cn'          => '用于WhatsApp登录——无法更改。', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Hint text below the locked phone chip on the driver profile page explaining it is their auth identity',
            ],
            [
                'key'         => 'profile.placeholder_name_callout',
                'ru'          => 'У вас временное имя. Укажите настоящее — оно будет отображаться при подаче заявок на грузы.',
                'kz'          => 'Сізде уақытша ат бар. Нақты атыңызды енгізіңіз — ол жүк өтінімдерінде көрсетіледі.', // TODO: verify with native speaker
                'cn'          => '您有一个临时名称。请输入真实姓名——它将在货物申请中显示。', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Invitation callout shown inside the form when a driver still has an auto-generated placeholder name',
            ],
            [
                'key'         => 'profile.field_name',
                'ru'          => 'Имя',
                'kz'          => 'Аты-жөні', // TODO: verify with native speaker
                'cn'          => '姓名', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Label for the name input on the profile form',
            ],
            [
                'key'         => 'profile.field_name_placeholder',
                'ru'          => 'Введите ваше имя',
                'kz'          => 'Атыңызды енгізіңіз', // TODO: verify with native speaker
                'cn'          => '请输入您的姓名', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Placeholder for the name input on the profile form',
            ],
            [
                'key'         => 'profile.field_name_hint_driver',
                'ru'          => 'Это имя увидят складские сотрудники при подаче заявок.',
                'kz'          => 'Бұл атты қойма қызметкерлері өтініш беру кезінде көреді.', // TODO: verify with native speaker
                'cn'          => '仓库员工在提交申请时将看到此名称。', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Helper text below the name input on the driver profile form',
            ],
            [
                'key'         => 'profile.field_email',
                'ru'          => 'Email',
                'kz'          => 'Email', // TODO: verify with native speaker
                'cn'          => '电子邮件', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Label for the email input on the non-driver profile form',
            ],
            [
                'key'         => 'profile.field_email_placeholder',
                'ru'          => 'your@email.com',
                'kz'          => 'your@email.com', // TODO: verify with native speaker
                'cn'          => 'your@email.com', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Placeholder for the email input on the non-driver profile form',
            ],
            [
                'key'         => 'profile.section_password',
                'ru'          => 'Смена пароля',
                'kz'          => 'Құпия сөзді өзгерту', // TODO: verify with native speaker
                'cn'          => '修改密码', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Section divider label above the password fields on the non-driver profile form',
            ],
            [
                'key'         => 'profile.field_password_new',
                'ru'          => 'Новый пароль',
                'kz'          => 'Жаңа құпия сөз', // TODO: verify with native speaker
                'cn'          => '新密码', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Label for the new password input on the non-driver profile form',
            ],
            [
                'key'         => 'profile.field_password_placeholder',
                'ru'          => 'Минимум 8 символов',
                'kz'          => 'Кемінде 8 таңба', // TODO: verify with native speaker
                'cn'          => '至少8个字符', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Placeholder for the new password input on the non-driver profile form',
            ],
            [
                'key'         => 'profile.field_password_hint',
                'ru'          => 'Оставьте пустым, чтобы не менять.',
                'kz'          => 'Өзгертпеу үшін бос қалдырыңыз.', // TODO: verify with native speaker
                'cn'          => '留空则不更改。', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Helper text below the new password input indicating blank = unchanged',
            ],
            [
                'key'         => 'profile.field_password_confirm',
                'ru'          => 'Подтверждение пароля',
                'kz'          => 'Құпия сөзді растаңыз', // TODO: verify with native speaker
                'cn'          => '确认密码', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Label for the password confirmation input on the non-driver profile form',
            ],
            [
                'key'         => 'profile.field_password_confirm_placeholder',
                'ru'          => 'Повторите пароль',
                'kz'          => 'Құпия сөзді қайталаңыз', // TODO: verify with native speaker
                'cn'          => '重复密码', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Placeholder for the password confirmation input on the non-driver profile form',
            ],
            [
                'key'         => 'profile.save_button',
                'ru'          => 'Сохранить',
                'kz'          => 'Сақтау', // TODO: verify with native speaker
                'cn'          => '保存', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Submit button label on the profile form',
            ],
            [
                'key'         => 'profile.save_submitting',
                'ru'          => 'Сохраняем...',
                'kz'          => 'Сақталуда...', // TODO: verify with native speaker
                'cn'          => '保存中…', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Submit button label while the profile form is submitting',
            ],
            [
                'key'         => 'profile.save_success',
                'ru'          => 'Профиль успешно обновлён.',
                'kz'          => 'Профиль сәтті жаңартылды.', // TODO: verify with native speaker
                'cn'          => '个人资料已成功更新。', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Success banner text shown inside the profile card after a successful save',
            ],
            [
                'key'         => 'profile.error_heading',
                'ru'          => 'Пожалуйста, исправьте ошибки ниже.',
                'kz'          => 'Төмендегі қателерді түзетіңіз.', // TODO: verify with native speaker
                'cn'          => '请更正以下错误。', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Heading inside the rose error banner on the profile form when validation fails',
            ],

            // ── Profile page (spec keys — canonical aliases used by the spec) ─────
            [
                'key'         => 'profile.title',
                'ru'          => 'Профиль — Silk Way',
                'kz'          => 'Профиль — Silk Way', // TODO: verify with native speaker
                'cn'          => '个人资料 — Silk Way', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Browser tab <title> for the profile page (spec key)',
            ],
            [
                'key'         => 'profile.heading',
                'ru'          => 'Мой профиль',
                'kz'          => 'Менің профилім', // TODO: verify with native speaker
                'cn'          => '我的资料', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'H1 page heading on the profile page (spec key)',
            ],
            [
                'key'         => 'profile.field_phone_label',
                'ru'          => 'Номер телефона',
                'kz'          => 'Телефон нөмірі', // TODO: verify with native speaker
                'cn'          => '手机号码', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Label above the read-only phone chip on the driver profile (spec key)',
            ],
            [
                'key'         => 'profile.field_phone_hint',
                'ru'          => 'Используется для входа через WhatsApp',
                'kz'          => 'WhatsApp арқылы кіру үшін пайдаланылады', // TODO: verify with native speaker
                'cn'          => '用于通过WhatsApp登录', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Hint under the locked phone number on the driver profile (spec key)',
            ],
            [
                'key'         => 'profile.field_password_confirmation',
                'ru'          => 'Подтверждение пароля',
                'kz'          => 'Құпия сөзді растау', // TODO: verify with native speaker
                'cn'          => '确认密码', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Label for password_confirmation field on the profile form (spec key)',
            ],
            [
                'key'         => 'profile.update_success',
                'ru'          => 'Профиль обновлён',
                'kz'          => 'Профиль жаңартылды', // TODO: verify with native speaker
                'cn'          => '资料已更新', // TODO: verify with native speaker
                'group'       => 'profile',
                'description' => 'Flash success message after a profile update (spec key)',
            ],

            // ── Notifications ────────────────────────────────────────────────────
            [
                'key'         => 'notifications.driver_approved',
                'ru'          => 'Silk Way: ваш аккаунт активирован. Теперь можно принимать заказы.',
                'kz'          => 'Silk Way: сіздің аккаунтыңыз белсендірілді. Енді тапсырыстарды қабылдауға болады.', // TODO: verify with native speaker
                'cn'          => 'Silk Way：您的账户已激活。现在可以接受订单了。', // TODO: verify with native speaker
                'group'       => 'notifications',
                'description' => 'WhatsApp text sent to a driver when admin approves their account',
            ],
            [
                // Placeholders: {cargo_title}, {driver_name}, {link}
                'key'         => 'notifications.cmr_uploaded',
                'ru'          => "CMR загружен и ожидает вашей проверки.\nГруз: {cargo_title}\nВодитель: {driver_name}\nПроверить: {link}",
                'kz'          => "CMR жүктелді және тексеруді күтуде.\nЖүк: {cargo_title}\nЖүргізуші: {driver_name}\nТексеру: {link}", // TODO: verify with native speaker
                'cn'          => "CMR已上传，等待您的审核。\n货物：{cargo_title}\n司机：{driver_name}\n查看：{link}", // TODO: verify with native speaker
                'group'       => 'notifications',
                'description' => 'WhatsApp text sent to the cargo owner (WE) when a driver uploads a CMR',
            ],

            // ── FCM push notifications ────────────────────────────────────────────
            // Placeholders use Laravel-style :name syntax (str_replace in call site).

            [
                'key'         => 'push.application_approved.title',
                'ru'          => 'Заявка подтверждена',
                'kz'          => 'Өтінім расталды', // TODO: verify with native speaker
                'cn'          => '申请已批准', // TODO: verify with native speaker
                'group'       => 'push',
                'description' => 'FCM push title sent to driver when their cargo application is approved',
            ],
            [
                // Placeholder: :cargo_route (e.g. "Алматы → Пекин")
                'key'         => 'push.application_approved.body',
                'ru'          => 'Ваша заявка на груз :cargo_route подтверждена. Можете выезжать!',
                'kz'          => ':cargo_route жүкіне өтініміңіз расталды. Жолға шығуға болады!', // TODO: verify with native speaker
                'cn'          => '您对货物 :cargo_route 的申请已批准。可以出发了！', // TODO: verify with native speaker
                'group'       => 'push',
                'description' => 'FCM push body sent to driver when their cargo application is approved',
            ],
            [
                'key'         => 'push.application_rejected.title',
                'ru'          => 'Заявка отклонена',
                'kz'          => 'Өтінім қабылданбады', // TODO: verify with native speaker
                'cn'          => '申请被拒绝', // TODO: verify with native speaker
                'group'       => 'push',
                'description' => 'FCM push title sent to driver when their cargo application is rejected',
            ],
            [
                // Placeholder: :cargo_route
                'key'         => 'push.application_rejected.body',
                'ru'          => 'Ваша заявка на груз :cargo_route была отклонена. Вы можете подать заявку на другой груз.',
                'kz'          => ':cargo_route жүкіне өтініміңіз қабылданбады. Басқа жүкке өтінім бере аласыз.', // TODO: verify with native speaker
                'cn'          => '您对货物 :cargo_route 的申请已被拒绝。您可以申请其他货物。', // TODO: verify with native speaker
                'group'       => 'push',
                'description' => 'FCM push body sent to driver when their cargo application is rejected',
            ],
            [
                'key'         => 'push.document_verified.title',
                'ru'          => 'Документ проверен',
                'kz'          => 'Құжат тексерілді', // TODO: verify with native speaker
                'cn'          => '文件已审核', // TODO: verify with native speaker
                'group'       => 'push',
                'description' => 'FCM push title sent to driver when admin approves one of their documents',
            ],
            [
                'key'         => 'push.document_verified.body',
                'ru'          => 'Один из ваших документов успешно проверен администратором.',
                'kz'          => 'Сіздің құжатыңыздың бірін әкімші тексерді.', // TODO: verify with native speaker
                'cn'          => '您的一份文件已经管理员审核通过。', // TODO: verify with native speaker
                'group'       => 'push',
                'description' => 'FCM push body sent to driver when admin approves one of their documents',
            ],
            [
                'key'         => 'push.document_rejected.title',
                'ru'          => 'Документ отклонён',
                'kz'          => 'Құжат қабылданбады', // TODO: verify with native speaker
                'cn'          => '文件被拒绝', // TODO: verify with native speaker
                'group'       => 'push',
                'description' => 'FCM push title sent to driver when admin rejects one of their documents',
            ],
            [
                'key'         => 'push.document_rejected.body',
                'ru'          => 'Один из ваших документов был отклонён. Проверьте причину и загрузите исправленный файл.',
                'kz'          => 'Сіздің құжатыңыздың бірі қабылданбады. Себебін тексеріп, түзетілген файлды жүктеңіз.', // TODO: verify with native speaker
                'cn'          => '您的一份文件被拒绝。请查看原因并上传修正后的文件。', // TODO: verify with native speaker
                'group'       => 'push',
                'description' => 'FCM push body sent to driver when admin rejects one of their documents',
            ],
            [
                'key'         => 'push.cmr_uploaded.title',
                'ru'          => 'Новый CMR на проверке',
                'kz'          => 'Жаңа CMR тексерілуде', // TODO: verify with native speaker
                'cn'          => '新CMR待审核', // TODO: verify with native speaker
                'group'       => 'push',
                'description' => 'FCM push title sent to cargo owner (WE) when driver uploads a CMR',
            ],
            [
                // Placeholder: :cargo_route
                'key'         => 'push.cmr_uploaded.body',
                'ru'          => 'Водитель загрузил CMR по маршруту :cargo_route. Требуется ваша проверка.',
                'kz'          => 'Жүргізуші :cargo_route бағыты бойынша CMR жүктеді. Тексеруіңіз қажет.', // TODO: verify with native speaker
                'cn'          => '司机已上传 :cargo_route 路线的CMR。需要您审核。', // TODO: verify with native speaker
                'group'       => 'push',
                'description' => 'FCM push body sent to cargo owner (WE) when driver uploads a CMR',
            ],
            [
                'key'         => 'push.cmr_confirmed.title',
                'ru'          => 'CMR подтверждён',
                'kz'          => 'CMR расталды', // TODO: verify with native speaker
                'cn'          => 'CMR已确认', // TODO: verify with native speaker
                'group'       => 'push',
                'description' => 'FCM push title sent to driver when reviewer confirms their CMR',
            ],
            [
                // Placeholder: :cargo_route
                'key'         => 'push.cmr_confirmed.body',
                'ru'          => 'Ваш CMR по маршруту :cargo_route подтверждён. Груз доставлен, спасибо!',
                'kz'          => ':cargo_route бағыты бойынша CMR-ыңыз расталды. Жүк жеткізілді, рахмет!', // TODO: verify with native speaker
                'cn'          => '您在 :cargo_route 路线的CMR已确认。货物已送达，谢谢！', // TODO: verify with native speaker
                'group'       => 'push',
                'description' => 'FCM push body sent to driver when reviewer confirms their CMR',
            ],
            [
                'key'         => 'push.cmr_rejected.title',
                'ru'          => 'CMR отклонён',
                'kz'          => 'CMR қабылданбады', // TODO: verify with native speaker
                'cn'          => 'CMR被拒绝', // TODO: verify with native speaker
                'group'       => 'push',
                'description' => 'FCM push title sent to driver when reviewer rejects their CMR',
            ],
            [
                // Placeholder: :cargo_route
                'key'         => 'push.cmr_rejected.body',
                'ru'          => 'Ваш CMR по маршруту :cargo_route был отклонён. Загрузите исправленный документ.',
                'kz'          => ':cargo_route бағыты бойынша CMR-ыңыз қабылданбады. Түзетілген құжатты жүктеңіз.', // TODO: verify with native speaker
                'cn'          => '您在 :cargo_route 路线的CMR已被拒绝。请上传更正后的文件。', // TODO: verify with native speaker
                'group'       => 'push',
                'description' => 'FCM push body sent to driver when reviewer rejects their CMR',
            ],

            // ── CMR UI strings (dashboard card, filter tab, row pill) ───────────
            [
                'key'         => 'cmr.dashboard_card_title',
                'ru'          => 'CMR на проверке',
                'kz'          => 'CMR тексерілуде', // TODO: verify with native speaker
                'cn'          => 'CMR待审核', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Label for the CMR-pending stat card on WE and admin dashboards',
            ],
            [
                'key'         => 'cmr.tab_pending',
                'ru'          => 'CMR на проверке',
                'kz'          => 'CMR тексерілуде', // TODO: verify with native speaker
                'cn'          => 'CMR待审核', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Label for the CMR-pending filter tab on the applications list',
            ],
            [
                'key'         => 'cmr.row_pill_pending',
                'ru'          => 'CMR ждёт проверки',
                'kz'          => 'CMR тексеруді күтуде', // TODO: verify with native speaker
                'cn'          => 'CMR待审核', // TODO: verify with native speaker
                'group'       => 'cmr',
                'description' => 'Inline pill shown next to cargo title when that cargo has a CMR pending review',
            ],

            // ── Navigation (user dropdown) ─────────────────────────────────────
            [
                'key'         => 'nav.profile',
                'ru'          => 'Профиль',
                'kz'          => 'Профиль', // TODO: verify with native speaker
                'cn'          => '个人资料', // TODO: verify with native speaker
                'group'       => 'nav',
                'description' => 'User-dropdown link label pointing to the profile page',
            ],

            // ── Warehouses ──────────────────────────────────────────────────────
            [
                'key'         => 'warehouse.title',
                'ru'          => 'Склады',
                'kz'          => 'Қоймалар', // TODO: verify with native speaker
                'cn'          => '仓库', // TODO: verify with native speaker
                'group'       => 'warehouse',
                'description' => 'Warehouse section heading in nav and page titles',
            ],
            [
                'key'         => 'warehouse.create',
                'ru'          => 'Добавить склад',
                'kz'          => 'Қойма қосу', // TODO: verify with native speaker
                'cn'          => '添加仓库', // TODO: verify with native speaker
                'group'       => 'warehouse',
                'description' => 'Button/link label to open the warehouse creation form',
            ],
            [
                'key'         => 'warehouse.edit',
                'ru'          => 'Редактировать склад',
                'kz'          => 'Қойманы өңдеу', // TODO: verify with native speaker
                'cn'          => '编辑仓库', // TODO: verify with native speaker
                'group'       => 'warehouse',
                'description' => 'Page title on warehouse edit form',
            ],
            [
                'key'         => 'warehouse.name_ru',
                'ru'          => 'Название (рус.)',
                'kz'          => 'Атауы (орысша)', // TODO: verify with native speaker
                'cn'          => '名称（俄语）', // TODO: verify with native speaker
                'group'       => 'warehouse',
                'description' => 'Label for the Russian warehouse name field',
            ],
            [
                'key'         => 'warehouse.name_kz',
                'ru'          => 'Название (каз.)',
                'kz'          => 'Атауы (қазақша)', // TODO: verify with native speaker
                'cn'          => '名称（哈萨克语）', // TODO: verify with native speaker
                'group'       => 'warehouse',
                'description' => 'Label for the Kazakh warehouse name field',
            ],
            [
                'key'         => 'warehouse.name_cn',
                'ru'          => 'Название (кит.)',
                'kz'          => 'Атауы (қытайша)', // TODO: verify with native speaker
                'cn'          => '名称（中文）', // TODO: verify with native speaker
                'group'       => 'warehouse',
                'description' => 'Label for the Chinese warehouse name field',
            ],
            [
                'key'         => 'warehouse.address',
                'ru'          => 'Адрес',
                'kz'          => 'Мекенжай', // TODO: verify with native speaker
                'cn'          => '地址', // TODO: verify with native speaker
                'group'       => 'warehouse',
                'description' => 'Label for the warehouse address field',
            ],
            [
                'key'         => 'warehouse.phone',
                'ru'          => 'Телефон',
                'kz'          => 'Телефон', // TODO: verify with native speaker
                'cn'          => '电话', // TODO: verify with native speaker
                'group'       => 'warehouse',
                'description' => 'Label for the warehouse phone field',
            ],
            [
                'key'         => 'warehouse.city',
                'ru'          => 'Город',
                'kz'          => 'Қала', // TODO: verify with native speaker
                'cn'          => '城市', // TODO: verify with native speaker
                'group'       => 'warehouse',
                'description' => 'Label for the city select on warehouse forms',
            ],
            [
                'key'         => 'warehouse.save',
                'ru'          => 'Сохранить склад',
                'kz'          => 'Қойманы сақтау', // TODO: verify with native speaker
                'cn'          => '保存仓库', // TODO: verify with native speaker
                'group'       => 'warehouse',
                'description' => 'Submit button label on warehouse create/edit forms',
            ],
            [
                'key'         => 'warehouse.no_warehouses_city',
                'ru'          => 'В этом городе нет складов',
                'kz'          => 'Бұл қалада қойма жоқ', // TODO: verify with native speaker
                'cn'          => '该城市没有仓库', // TODO: verify with native speaker
                'group'       => 'warehouse',
                'description' => 'Header of the amber banner shown when selected city has no warehouses',
            ],
            [
                'key'         => 'warehouse.create_prompt',
                'ru'          => 'Создайте склад, чтобы продолжить',
                'kz'          => 'Жалғастыру үшін қойма жасаңыз', // TODO: verify with native speaker
                'cn'          => '创建仓库以继续', // TODO: verify with native speaker
                'group'       => 'warehouse',
                'description' => 'Sub-text in the amber no-warehouse banner on cargo create/edit',
            ],
            [
                'key'         => 'warehouse.create_link',
                'ru'          => 'Создать склад',
                'kz'          => 'Қойма жасау', // TODO: verify with native speaker
                'cn'          => '创建仓库', // TODO: verify with native speaker
                'group'       => 'warehouse',
                'description' => 'Link text inside the no-warehouse amber banner',
            ],
            [
                'key'         => 'warehouse.autocreate_note',
                'ru'          => 'Вернитесь на эту страницу и выберите город снова',
                'kz'          => 'Осы бетке оралып, қаланы қайта таңдаңыз', // TODO: verify with native speaker
                'cn'          => '返回此页面并重新选择城市', // TODO: verify with native speaker
                'group'       => 'warehouse',
                'description' => 'Instructional note below the create-warehouse link in the amber banner',
            ],
            [
                'key'         => 'warehouse.delete_confirm',
                'ru'          => 'Удалить склад «:name»? Это действие нельзя отменить.',
                'kz'          => '«:name» қоймасын жою керек пе? Бұл әрекетті қайтару мүмкін емес.', // TODO: verify with native speaker
                'cn'          => '删除仓库「:name」？此操作无法撤销。', // TODO: verify with native speaker
                'group'       => 'warehouse',
                'description' => 'Inline delete confirmation text shown per row in the warehouse list',
            ],
            [
                'key'         => 'warehouse.delete_blocked',
                'ru'          => 'Этот склад используется в :count грузах. Удаление невозможно.',
                'kz'          => 'Бұл қойма :count жүкте пайдаланылуда. Жою мүмкін емес.', // TODO: verify with native speaker
                'cn'          => '该仓库在 :count 个货物中被使用，无法删除。', // TODO: verify with native speaker
                'group'       => 'warehouse',
                'description' => 'Error shown when trying to delete a warehouse referenced by cargo',
            ],
            [
                'key'         => 'warehouse.access_denied',
                'ru'          => 'Доступ запрещён. Этот склад не принадлежит вашей учётной записи.',
                'kz'          => 'Қол жеткізу тыйым салынды. Бұл қойма сіздің есептік жазбаңызға тиесілі емес.', // TODO: verify with native speaker
                'cn'          => '访问被拒绝。该仓库不属于您的账户。', // TODO: verify with native speaker
                'group'       => 'warehouse',
                'description' => 'Authorization error shown when WE tries to access another user\'s warehouse',
            ],
            [
                'key'         => 'warehouse.empty_own',
                'ru'          => 'У вас пока нет складов.',
                'kz'          => 'Сізде әлі қойма жоқ.', // TODO: verify with native speaker
                'cn'          => '您目前没有仓库。', // TODO: verify with native speaker
                'group'       => 'warehouse',
                'description' => 'Empty-state message shown to WE who has no warehouses',
            ],
            [
                'key'         => 'warehouse.empty_filtered',
                'ru'          => 'Склады не найдены. Попробуйте изменить фильтры.',
                'kz'          => 'Қоймалар табылмады. Сүзгілерді өзгертіп көріңіз.', // TODO: verify with native speaker
                'cn'          => '未找到仓库。请尝试更改筛选条件。', // TODO: verify with native speaker
                'group'       => 'warehouse',
                'description' => 'Empty-state message shown to admin when filters yield no results',
            ],

            // ── Cargo — warehouse fields ──────────────────────────────────────
            [
                'key'         => 'cargo.from_warehouse',
                'ru'          => 'Склад отправки',
                'kz'          => 'Жіберу қоймасы', // TODO: verify with native speaker
                'cn'          => '发货仓库', // TODO: verify with native speaker
                'group'       => 'cargo',
                'description' => 'Label for the from-warehouse select on cargo create/edit forms',
            ],
            [
                'key'         => 'cargo.to_warehouse',
                'ru'          => 'Склад назначения',
                'kz'          => 'Тағайындалған қойма', // TODO: verify with native speaker
                'cn'          => '目的地仓库', // TODO: verify with native speaker
                'group'       => 'cargo',
                'description' => 'Label for the to-warehouse select on cargo create/edit forms',
            ],
            [
                'key'         => 'cargo.warehouse_label',
                'ru'          => 'Склад: :name',
                'kz'          => 'Қойма: :name', // TODO: verify with native speaker
                'cn'          => '仓库：:name', // TODO: verify with native speaker
                'group'       => 'cargo',
                'description' => 'Readonly badge label shown when a city has exactly one warehouse',
            ],

            // ─── Legal ────────────────────────────────────────────────────────
            [
                'key'         => 'legal.privacy_link',
                'ru'          => 'Политика конфиденциальности',
                'kz'          => 'Құпиялылық саясаты', // TODO: verify with native speaker
                'cn'          => '隐私政策', // TODO: verify with native speaker
                'group'       => 'legal',
                'description' => 'Privacy policy page link label — footer and registration pages',
            ],
            [
                'key'         => 'legal.privacy_consent',
                'ru'          => 'Нажимая «Зарегистрироваться», вы соглашаетесь с нашей :link.',
                'kz'          => '«Тіркелу» батырмасын басу арқылы сіз біздің :link келісесіз.', // TODO: verify with native speaker
                'cn'          => '点击"注册"即表示您同意我们的:link。', // TODO: verify with native speaker
                'group'       => 'legal',
                'description' => 'Consent line below registration submit button — :link is replaced with an <a> tag in the view',
            ],
            [
                'key'         => 'legal.privacy_consent_link',
                'ru'          => 'Политикой конфиденциальности',
                'kz'          => 'Құпиялылық саясатымен', // TODO: verify with native speaker
                'cn'          => '隐私政策', // TODO: verify with native speaker
                'group'       => 'legal',
                'description' => 'Linked text within the registration consent line (instrumental case for RU grammar)',
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
