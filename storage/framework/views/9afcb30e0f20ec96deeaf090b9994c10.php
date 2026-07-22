<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Astabaya'); ?> - Aplikasi Statistik Kota Surabaya</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- CSS Files -->
    <?php echo $__env->yieldPushContent('styles'); ?>
    
    <link rel="shortcut icon" href="<?php echo e(asset('images/Aastabaya-favicon(2).png')); ?>">
</head>
<body>
    <?php echo $__env->yieldContent('content'); ?>
    
    <!-- Scripts -->
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>


<?php /**PATH D:\laragon\www\astabaya\resources\views/layouts/app.blade.php ENDPATH**/ ?>