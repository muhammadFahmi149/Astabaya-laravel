<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'chartId' => '',
    'title' => '',
    'class' => ''
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'chartId' => '',
    'title' => '',
    'class' => ''
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<button 
    type="button" 
    class="btn btn-sm btn-outline-primary share-chart-button <?php echo e($class); ?>" 
    data-share
    data-share-chart="<?php echo e($chartId); ?>" 
    data-share-title="<?php echo e($title); ?>"
    title="Bagikan <?php echo e($title); ?>"
    aria-label="Bagikan <?php echo e($title); ?>"
>
    <i class="fas fa-share-alt"></i>
    <span>Bagikan</span>
</button>
<?php /**PATH C:\xampp\htdocs\Astabaya-laravel\resources\views/components/chart-share-button.blade.php ENDPATH**/ ?>