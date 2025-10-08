<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Детали перевода</h1>
            <div class="flex space-x-3">
                <a href="<?php echo e(route('admin.translations.edit', $translation)); ?>" 
                   class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    <?php echo e(__('edit')); ?>

                </a>
                <a href="<?php echo e(route('admin.translations.index')); ?>" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    <?php echo e(__('back')); ?>

                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Заголовок -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900"><?php echo e($translation->key); ?></h2>
                        <?php if($translation->description): ?>
                            <p class="text-sm text-gray-600 mt-1"><?php echo e($translation->description); ?></p>
                        <?php endif; ?>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <?php echo e($translation->group); ?>

                    </span>
                </div>
            </div>

            <!-- Детали перевода -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Русский -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <span class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center text-white text-xs font-bold mr-2">
                                RU
                            </span>
                            <h3 class="text-lg font-medium text-gray-900">Русский</h3>
                        </div>
                        <div class="bg-white rounded border p-3 min-h-[100px]">
                            <p class="text-gray-800"><?php echo e($translation->rus); ?></p>
                        </div>
                    </div>

                    <!-- Казахский -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <span class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold mr-2">
                                KK
                            </span>
                            <h3 class="text-lg font-medium text-gray-900">Қазақша</h3>
                        </div>
                        <div class="bg-white rounded border p-3 min-h-[100px]">
                            <p class="text-gray-800"><?php echo e($translation->kaz); ?></p>
                        </div>
                    </div>

                    <!-- Китайский -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <span class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center text-white text-xs font-bold mr-2">
                                中
                            </span>
                            <h3 class="text-lg font-medium text-gray-900">中文</h3>
                        </div>
                        <div class="bg-white rounded border p-3 min-h-[100px]">
                            <p class="text-gray-800"><?php echo e($translation->chn); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Метаинформация -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Метаинформация</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ключ перевода</label>
                            <p class="mt-1 text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">
                                <?php echo e($translation->key); ?>

                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Группа</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e(ucfirst($translation->group)); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Создан</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($translation->created_at->format('d.m.Y H:i:s')); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Обновлен</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($translation->updated_at->format('d.m.Y H:i:s')); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Предварительный просмотр -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Предварительный просмотр</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm text-gray-600 mb-2">Использование в коде:</div>
                        <div class="bg-gray-900 text-green-400 p-3 rounded font-mono text-sm overflow-x-auto">
                            <span class="text-gray-400">// В шаблоне:</span><br>
                            <span class="text-yellow-400"><?php echo e('{{ __(' . "'" . $translation->key . "'" . ')); ?>' }}</span><br><br>
                            <span class="text-gray-400">// В контроллере:</span><br>
                            <span class="text-yellow-400">__('<?php echo e($translation->key); ?>')</span><br><br>
                            <span class="text-gray-400">// В хелпере:</span><br>
                            <span class="text-yellow-400">LocalizationHelper::t('<?php echo e($translation->key); ?>')</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/zhandosbaukei/Desktop/projects/silk-way/silk-way-app/resources/views/admin/translations/show.blade.php ENDPATH**/ ?>