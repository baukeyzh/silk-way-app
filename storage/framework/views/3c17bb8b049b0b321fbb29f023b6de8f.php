<?php $__env->startSection('title', 'Просмотр машины'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center mb-6">
            <button type="button" 
                    onclick="history.back()"
                    class="text-gray-600 hover:text-gray-900 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <h1 class="text-3xl font-bold text-gray-900">Просмотр машины</h1>
        </div>

        <?php if(session('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <!-- Заголовок -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">
                            <?php echo e($car->brand); ?> <?php echo e($car->model); ?>

                        </h2>
                        <p class="text-lg text-gray-600"><?php echo e($car->license_plate); ?></p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full <?php echo e($car->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                            <?php echo e($car->is_active ? 'Активна' : 'Неактивна'); ?>

                        </span>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Основная информация -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Основная информация</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Водитель</dt>
                                <dd class="mt-1 text-sm text-gray-900"><?php echo e($car->user->name); ?></dd>
                                <dd class="text-sm text-gray-600"><?php echo e($car->user->email); ?></dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Марка</dt>
                                <dd class="mt-1 text-sm text-gray-900"><?php echo e($car->brand); ?></dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Модель</dt>
                                <dd class="mt-1 text-sm text-gray-900"><?php echo e($car->model); ?></dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Гос. номер</dt>
                                <dd class="mt-1 text-sm text-gray-900"><?php echo e($car->license_plate); ?></dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Максимальный вес</dt>
                                <dd class="mt-1 text-sm text-gray-900"><?php echo e($car->max_weight); ?> тонн</dd>
                            </div>
                        </div>
                    </div>

                    <!-- Информация о прицепе -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Информация о прицепе</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Тип прицепа</dt>
                                <dd class="mt-1 text-sm text-gray-900"><?php echo e($car->trailer_type_ru); ?></dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Габариты</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    Длина: <?php echo e($car->trailer_length); ?> м<br>
                                    Ширина: <?php echo e($car->trailer_width); ?> м<br>
                                    Высота: <?php echo e($car->trailer_height); ?> м
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Объем</dt>
                                <dd class="mt-1 text-sm text-gray-900"><?php echo e($car->trailer_volume); ?> м³</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Документ ПДД -->
                <?php if($car->vehicle_document): ?>
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Документ ПДД</h3>
                        <div class="flex items-center space-x-4">
                            <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-900">Документ загружен</p>
                                <a href="<?php echo e(Storage::url($car->vehicle_document)); ?>" target="_blank" 
                                   class="text-sm text-blue-600 hover:text-blue-800">
                                    Скачать PDF
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Действия -->
                <?php if(auth()->user()->id === $car->user_id): ?>
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex space-x-4">
                            <a href="<?php echo e(route('cars.edit', $car)); ?>" 
                               class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Редактировать
                            </a>
                            
                            <form action="<?php echo e(route('cars.toggle-status', $car)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" 
                                        class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                    <?php echo e($car->is_active ? 'Деактивировать' : 'Активировать'); ?>

                                </button>
                            </form>
                            
                            <form action="<?php echo e(route('cars.destroy', $car)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" 
                                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                        onclick="return confirm('Вы уверены, что хотите удалить эту машину?')">
                                    Удалить
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/zhandosbaukei/Desktop/projects/silk-way/silk-way-app/resources/views/cars/show.blade.php ENDPATH**/ ?>