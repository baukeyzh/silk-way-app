<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900"><?php echo e(__('edit_translation')); ?></h1>
            <a href="<?php echo e(route('admin.translations.index')); ?>" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <?php echo e(__('back')); ?>

            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="<?php echo e(route('admin.translations.update', $translation)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Ключ перевода (только для чтения) -->
                    <div class="md:col-span-2">
                        <label for="key" class="block text-sm font-medium text-gray-700 mb-2">
                            <?php echo e(__('translation_key')); ?>

                        </label>
                        <input type="text" 
                               id="key" 
                               value="<?php echo e($translation->key); ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100"
                               readonly>
                        <p class="mt-1 text-sm text-gray-500">Ключ перевода нельзя изменить</p>
                    </div>

                    <!-- Группа -->
                    <div class="md:col-span-2">
                        <label for="group" class="block text-sm font-medium text-gray-700 mb-2">
                            <?php echo e(__('translation_group')); ?> *
                        </label>
                        <select id="group" 
                                name="group" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['group'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($group); ?>" <?php echo e(old('group', $translation->group) == $group ? 'selected' : ''); ?>>
                                    <?php echo e(ucfirst($group)); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <option value="new_group" <?php echo e(old('group') == 'new_group' ? 'selected' : ''); ?>>
                                Новая группа
                            </option>
                        </select>
                        <?php $__errorArgs = ['group'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Новая группа (показывается только если выбрана опция "Новая группа") -->
                    <div class="md:col-span-2" id="new_group_field" style="display: none;">
                        <label for="new_group_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Название новой группы
                        </label>
                        <input type="text" 
                               id="new_group_name" 
                               name="new_group_name" 
                               value="<?php echo e(old('new_group_name')); ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Введите название новой группы">
                    </div>

                    <!-- Описание -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            <?php echo e(__('translation_description')); ?>

                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  placeholder="Опишите, для чего используется этот перевод"><?php echo e(old('description', $translation->description)); ?></textarea>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Русский перевод -->
                    <div>
                        <label for="rus" class="block text-sm font-medium text-gray-700 mb-2">
                            Русский *
                        </label>
                        <textarea id="rus" 
                                  name="rus" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['rus'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  placeholder="Введите перевод на русском языке"><?php echo e(old('rus', $translation->rus)); ?></textarea>
                        <?php $__errorArgs = ['rus'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Казахский перевод -->
                    <div>
                        <label for="kaz" class="block text-sm font-medium text-gray-700 mb-2">
                            Қазақша *
                        </label>
                        <textarea id="kaz" 
                                  name="kaz" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['kaz'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  placeholder="Введите перевод на казахском языке"><?php echo e(old('kaz', $translation->kaz)); ?></textarea>
                        <?php $__errorArgs = ['kaz'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Китайский перевод -->
                    <div class="md:col-span-2">
                        <label for="chn" class="block text-sm font-medium text-gray-700 mb-2">
                            中文 *
                        </label>
                        <textarea id="chn" 
                                  name="chn" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['chn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  placeholder="Введите перевод на китайском языке"><?php echo e(old('chn', $translation->chn)); ?></textarea>
                        <?php $__errorArgs = ['chn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <a href="<?php echo e(route('admin.translations.index')); ?>" 
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        <?php echo e(__('cancel')); ?>

                    </a>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <?php echo e(__('save')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const groupSelect = document.getElementById('group');
    const newGroupField = document.getElementById('new_group_field');
    const newGroupNameInput = document.getElementById('new_group_name');
    
    function toggleNewGroupField() {
        if (groupSelect.value === 'new_group') {
            newGroupField.style.display = 'block';
            newGroupNameInput.required = true;
        } else {
            newGroupField.style.display = 'none';
            newGroupNameInput.required = false;
        }
    }
    
    groupSelect.addEventListener('change', toggleNewGroupField);
    toggleNewGroupField(); // Вызываем при загрузке страницы
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/zhandosbaukei/Desktop/projects/silk-way/silk-way-app/resources/views/admin/translations/edit.blade.php ENDPATH**/ ?>