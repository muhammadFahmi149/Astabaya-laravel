<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
  <h2 class="font-weight-bold mb-5">Dashboard Statistik Astabaya Surabaya</h2>

  <div class="row mb-4">
    <div class="col-md-3 mb-3">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
          <h5 class="card-title">Inflasi</h5>
          <p class="card-text text-muted">Perubahan harga barang dan jasa</p>
          <a href="<?php echo e(route('inflasi')); ?>" class="btn btn-primary btn-sm">Lihat Data</a>
        </div>
      </div>
    </div>

    <div class="col-md-3 mb-3">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
          <h5 class="card-title">PDRB</h5>
          <p class="card-text text-muted">Produk Domestik Regional Bruto</p>
          <a href="<?php echo e(route('pdrb')); ?>" class="btn btn-primary btn-sm">Lihat Data</a>
        </div>
      </div>
    </div>

    <div class="col-md-3 mb-3">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
          <h5 class="card-title">Kemiskinan</h5>
          <p class="card-text text-muted">Data kemiskinan di Surabaya</p>
          <a href="<?php echo e(route('kemiskinan')); ?>" class="btn btn-primary btn-sm">Lihat Data</a>
        </div>
      </div>
    </div>

    <div class="col-md-3 mb-3">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
          <h5 class="card-title">Kependudukan</h5>
          <p class="card-text text-muted">Demografi penduduk Surabaya</p>
          <a href="<?php echo e(route('kependudukan')); ?>" class="btn btn-primary btn-sm">Lihat Data</a>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-4">
    <div class="col-md-3 mb-3">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
          <h5 class="card-title">Ketenagakerjaan</h5>
          <p class="card-text text-muted">Data ketenagakerjaan</p>
          <a href="<?php echo e(route('ketenagakerjaan')); ?>" class="btn btn-primary btn-sm">Lihat Data</a>
        </div>
      </div>
    </div>

    <div class="col-md-3 mb-3">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
          <h5 class="card-title">Hotel Occupancy</h5>
          <p class="card-text text-muted">Data okupansi hotel</p>
          <a href="<?php echo e(route('hotel_occupancy')); ?>" class="btn btn-primary btn-sm">Lihat Data</a>
        </div>
      </div>
    </div>

    <div class="col-md-3 mb-3">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
          <h5 class="card-title">Gini Ratio</h5>
          <p class="card-text text-muted">Indeks ketimpangan pendapatan</p>
          <a href="<?php echo e(route('gini_ratio')); ?>" class="btn btn-primary btn-sm">Lihat Data</a>
        </div>
      </div>
    </div>

    <div class="col-md-3 mb-3">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
          <h5 class="card-title">IPM</h5>
          <p class="card-text text-muted">Indeks Pembangunan Manusia</p>
          <a href="<?php echo e(route('indeks_pembangunan_manusia')); ?>" class="btn btn-primary btn-sm">Lihat Data</a>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-4 mb-3">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
          <h5 class="card-title">Berita</h5>
          <p class="card-text text-muted">Berita terbaru dari BPS</p>
          <a href="<?php echo e(route('news')); ?>" class="btn btn-primary btn-sm">Lihat Berita</a>
        </div>
      </div>
    </div>

    <div class="col-md-4 mb-3">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
          <h5 class="card-title">Publikasi</h5>
          <p class="card-text text-muted">Publikasi dari BPS</p>
          <a href="<?php echo e(route('publications')); ?>" class="btn btn-primary btn-sm">Lihat Publikasi</a>
        </div>
      </div>
    </div>

    <div class="col-md-4 mb-3">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body text-center">
          <h5 class="card-title">Infografis</h5>
          <p class="card-text text-muted">Infografis statistik</p>
          <a href="<?php echo e(route('infographics')); ?>" class="btn btn-primary btn-sm">Lihat Infografis</a>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .card {
    transition: transform 0.2s, box-shadow 0.2s;
  }
  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
  }
  .card-body {
    padding: 2rem;
  }
</style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Astabaya-laravel\resources\views/dashboard/dashboard.blade.php ENDPATH**/ ?>