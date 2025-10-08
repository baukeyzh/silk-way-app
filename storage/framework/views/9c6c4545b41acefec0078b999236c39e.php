<?php $__env->startSection('title', 'Добавить машину'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center mb-6">
            <button type="button" 
                    onclick="history.back()"
                    class="text-gray-600 hover:text-gray-900 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <h1 class="text-3xl font-bold text-gray-900">Добавить машину</h1>
        </div>

        <?php if($errors->any()): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="<?php echo e(route('cars.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Марка -->
                    <div>
                        <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">
                            Марка автомобиля *
                        </label>
                        <input type="text" name="brand" id="brand" value="<?php echo e(old('brand')); ?>" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Модель -->
                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700 mb-2">
                            Модель автомобиля *
                        </label>
                        <input type="text" name="model" id="model" value="<?php echo e(old('model')); ?>" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Гос. номер -->
                    <div>
                        <label for="license_plate" class="block text-sm font-medium text-gray-700 mb-2">
                            Государственный номер *
                        </label>
                        <input type="text" name="license_plate" id="license_plate" value="<?php echo e(old('license_plate')); ?>" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="А123БВ77">
                    </div>

                    <!-- Максимальный вес -->
                    <div>
                        <label for="max_weight" class="block text-sm font-medium text-gray-700 mb-2">
                            Максимальный вес (тонны) *
                        </label>
                        <input type="number" name="max_weight" id="max_weight" value="<?php echo e(old('max_weight')); ?>" required
                               step="0.1" min="0.1" max="100"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Длина прицепа -->
                    <div>
                        <label for="trailer_length" class="block text-sm font-medium text-gray-700 mb-2">
                            Длина прицепа (метры) *
                        </label>
                        <input type="number" name="trailer_length" id="trailer_length" value="<?php echo e(old('trailer_length')); ?>" required
                               step="0.1" min="0.1" max="50"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Ширина прицепа -->
                    <div>
                        <label for="trailer_width" class="block text-sm font-medium text-gray-700 mb-2">
                            Ширина прицепа (метры) *
                        </label>
                        <input type="number" name="trailer_width" id="trailer_width" value="<?php echo e(old('trailer_width')); ?>" required
                               step="0.1" min="0.1" max="10"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Высота прицепа -->
                    <div>
                        <label for="trailer_height" class="block text-sm font-medium text-gray-700 mb-2">
                            Высота прицепа (метры) *
                        </label>
                        <input type="number" name="trailer_height" id="trailer_height" value="<?php echo e(old('trailer_height')); ?>" required
                               step="0.1" min="0.1" max="10"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Тип прицепа -->
                    <div>
                        <label for="trailer_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Тип прицепа *
                        </label>
                        <select name="trailer_type" id="trailer_type" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Выберите тип прицепа</option>
                            <?php $__currentLoopData = $trailerTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>" <?php echo e(old('trailer_type') == $key ? 'selected' : ''); ?>>
                                    <?php echo e($value); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <!-- Документ ПДД -->
                <div class="mt-6">
                    <label for="vehicle_document" class="block text-sm font-medium text-gray-700 mb-2">
                        Документ ПДД (PDF)
                    </label>
                    <input type="file" name="vehicle_document" id="vehicle_document" accept=".pdf"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Максимальный размер: 10 МБ</p>
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    <button type="button" 
                            onclick="history.back()"
                            class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Отмена
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Добавить машину
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/zhandosbaukei/Desktop/projects/silk-way/silk-way-app/resources/views/cars/create.blade.php ENDPATH**/ ?>