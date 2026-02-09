<?php
    // Default values
    $title = $title ?? '';
    $url = $url ?? '';
    $contentType = $contentType ?? 'default';
    $size = $size ?? 'sm';
    $variant = $variant ?? 'outline-secondary';
    $showText = $showText ?? true;
    $customClass = $class ?? '';
    $icon = $iconClass ?? $icon ?? 'bi-share';
    $text = $text ?? 'Bagikan';
    $textClass = $textClass ?? '';
    
    // Determine data attributes based on content type
    $dataTitleAttr = '';
    $dataUrlAttr = '';
    
    switch($contentType) {
        case 'infographic':
            $dataTitleAttr = 'data-infographic-title';
            $dataUrlAttr = 'data-infographic-url';
            break;
        case 'publication':
            $dataTitleAttr = 'data-pub-title';
            $dataUrlAttr = 'data-pub-url';
            break;
        case 'news':
            $dataTitleAttr = 'data-news-title';
            $dataUrlAttr = 'data-news-url';
            break;
        default:
            $dataTitleAttr = 'data-share-title';
            $dataUrlAttr = 'data-share-url';
            break;
    }
    
    // Build class string
    $buttonClasses = "btn btn-{$size} btn-{$variant} share-btn";
    if ($customClass) {
        $buttonClasses .= " {$customClass}";
    }
    
    // Ensure URL is complete
    if ($url && strpos($url, 'http://') !== 0 && strpos($url, 'https://') !== 0) {
        $url = url($url);
    }
?>

<button 
    type="button" 
    class="<?php echo e($buttonClasses); ?>"
    <?php echo e($dataTitleAttr); ?>="<?php echo e($title); ?>"
    <?php echo e($dataUrlAttr); ?>="<?php echo e($url); ?>"
    <?php if(isset($onclick)): ?> onclick="<?php echo e($onclick); ?>" <?php endif; ?>
    <?php if(isset($id)): ?> id="<?php echo e($id); ?>" <?php endif; ?>
    <?php if(isset($dataAttributes)): ?>
        <?php $__currentLoopData = $dataAttributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            data-<?php echo e($key); ?>="<?php echo e($value); ?>"
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
    title="Bagikan <?php echo e($title ?: 'konten'); ?>"
    aria-label="Bagikan <?php echo e($title ?: 'konten'); ?>"
>
    <i class="<?php echo e($icon); ?>"></i>
    <?php if($showText): ?>
        <span class="share-btn-text <?php echo e($textClass); ?>"><?php echo e($text); ?></span>
    <?php endif; ?>
</button>

<?php /**PATH C:\xampp\htdocs\Astabaya-laravel\resources\views/components/share-button.blade.php ENDPATH**/ ?>