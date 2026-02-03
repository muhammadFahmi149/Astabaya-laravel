<?php $__env->startSection('title', 'Aastabaya'); ?>

<?php $__env->startPush('styles'); ?>
    <!-- Bootstrap core CSS -->
    <link href="<?php echo e(asset('templatemo_562_space_dynamic/vendor/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet">
    
    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="<?php echo e(asset('templatemo_562_space_dynamic/assets/css/fontawesome.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('templatemo_562_space_dynamic/assets/css/templatemo-space-dynamic.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('templatemo_562_space_dynamic/assets/css/animated.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('templatemo_562_space_dynamic/assets/css/owl.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/styles.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <!-- ***** Preloader Start ***** -->
    <div id="js-preloader" class="js-preloader">
        <div class="preloader-inner">
            <span class="dot"></span>
            <div class="dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    <!-- ***** Preloader End ***** -->

    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky wow slideInDown" data-wow-duration="0.75s" data-wow-delay="0s">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="<?php echo e(route('index')); ?>" class="logo">
                            <h4>As<span>tabaya</span></h4>
                        </a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class="scroll-to-section"><a href="#top" class="active">Beranda</a></li>
                            <li class="scroll-to-section"><a href="#about">Tentang Kami</a></li>
                            <li class="scroll-to-section"><a href="#services">Pelayanan</a></li>
                            <li class="scroll-to-section"><a href="#contact">Pesan</a></li>
                            <li class="scroll-to-section">
                        </ul>
                        <a class="menu-trigger">
                            <span>Menu</span>
                        </a>
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->

    <div class="main-banner wow fadeIn" id="top" data-wow-duration="1s" data-wow-delay="0.5s">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <!-- KOLOM KIRI -->
                        <div class="col-lg-6 d-flex flex-column order-2 order-lg-1">
                            <div class="left-content header-text wow fadeInLeft" data-wow-duration="1s" data-wow-delay="1s">
                                <h2>Selamat Datang di <em>Aastabaya</em></h2>
                                <h6 class="mt-3">Aplikasi Statistik Kota Surabaya</h6>
                                <p>Aastabaya merupakan platform yang menyajikan data dan indikator strategis Kota Surabaya, meliputi inflasi, IPM, kemiskinan, gini ratio, ketenagakerjaan, PDRB, dan ekonomi daerah secara akurat dan terkini.</p>
                            </div>
                            <a class="start-btn align-self-start mt-3" href="<?php echo e(route('dashboard')); ?>" style="background-color: #03a4ed; display: inline-block;">Lihat Selengkapnya</a>
                        </div>
                        
                        <!-- KOLOM KANAN -->
                        <div class="col-lg-6 order-1 order-lg-2 mb-4 mb-lg-0">
                            <div class="right-image wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.5s">
                                <img src="<?php echo e(asset('images/img/welcome.png')); ?>" alt="team meeting" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="about" class="about-us section">
        <div class="container pt-4 pb-5">
            <div class="row">
                <div class="col-lg-4">
                    <div class="left-image wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s">
                        <img class="person-graphic" src="<?php echo e(asset('templatemo_562_space_dynamic/assets/images/tab_kemiskinan.png')); ?>" alt="person graphic">
                    </div>
                </div>
                <div class="col-lg-8 align-self-center">
                    <div class="services pb-5">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="item wow fadeIn" data-wow-duration="1s" data-wow-delay="0.5s">
                                    <div class="icon">
                                        <img src="<?php echo e(asset('templatemo_562_space_dynamic/assets/images/inflasi-astabaya.png')); ?>" alt="reporting">
                                    </div>
                                    <div class="right-text">
                                        <h4>Inflasi</h4>
                                        <p>Perkembangan harga kebutuhan masyarakat Surabaya.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="item wow fadeIn" data-wow-duration="1s" data-wow-delay="0.7s">
                                    <div class="icon">
                                        <img src="<?php echo e(asset('templatemo_562_space_dynamic/assets/images/IPM-astabaya.png')); ?>" alt="">
                                    </div>
                                    <div class="right-text">
                                        <h4>IPM</h4>
                                        <p>Kualitas hidup penduduk dari sisi pendidikan dan kesehatan.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="item wow fadeIn" data-wow-duration="1s" data-wow-delay="0.9s">
                                    <div class="icon">
                                        <img src="<?php echo e(asset('templatemo_562_space_dynamic/assets/images/kemiskinan-astabaya.png')); ?>" alt="">
                                    </div>
                                    <div class="right-text">
                                        <h4>Kemiskinan</h4>
                                        <p>Kondisi kesejahteraan dan jumlah penduduk miskin.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="item wow fadeIn" data-wow-duration="1s" data-wow-delay="1.1s">
                                    <div class="icon">
                                        <img src="<?php echo e(asset('templatemo_562_space_dynamic/assets/images/gini-ratio-astabaya.png')); ?>" alt="">
                                    </div>
                                    <div class="right-text">
                                        <h4>Gini Ratio</h4>
                                        <p>Tingkat ketimpangan pendapatan penduduk.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="item wow fadeIn" data-wow-duration="1s" data-wow-delay="1.1s">
                                    <div class="icon">
                                        <img src="<?php echo e(asset('templatemo_562_space_dynamic/assets/images/Ketenagakerjaan-astabaya.png')); ?>" alt="">
                                    </div>
                                    <div class="right-text">
                                        <h4>Ketenagakerjaan</h4>
                                        <p>Informasi pasar kerja dan pengangguran.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="item wow fadeIn" data-wow-duration="1s" data-wow-delay="1.1s">
                                    <div class="icon">
                                        <img src="<?php echo e(asset('templatemo_562_space_dynamic/assets/images/PDRB-astabaya.png')); ?>" alt="">
                                    </div>
                                    <div class="right-text">
                                        <h4>PDRB</h4>
                                        <p>Nilai total semua barang dan jasa yang dihasilkan.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="services" class="our-services section" style="padding: 110px 0px">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 align-self-start wow fadeInLeft" data-wow-duration="1s" data-wow-delay="0.2s">
                    <div class="left-image">
                        <img src="<?php echo e(asset('templatemo_562_space_dynamic/assets/images/tab_kemiskinan.png')); ?>" alt="">
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.2s">
                    <div class="section-heading">
                        <h2>Analisis Indikator <em>Strategis</em> Surabaya dengan Dashboard <span>Interaktif</span></h2>
                        <p>
                            Kami menyajikan dashboard interaktif yang memvisualisasikan data strategis Surabaya secara real-time. Mulai dari perkembangan ekonomi, kesejahteraan penduduk, hingga pemerataan pendapatan, semuanya tersedia dalam grafik yang
                            intuitif dan mudah dipahami.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="contact" class="contact-us section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 align-self-center wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay="0.25s">
                    <div class="section-heading">
                        <h2>Hubungi Kami Terkait Informasi Statistik Kota Surabaya</h2>
                        <p>Formulir ini dapat digunakan untuk permintaan data, klarifikasi indikator, atau penyampaian masukan terkait pelayanan informasi statistik melalui Aastabaya.</p>
                        <div class="phone-info">
                            <h4>
                                Layanan informasi:
                                <span><i class="fa fa-phone"></i> <a href="#">Telp (62-31) 82516020</a></span>
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInRight" data-wow-duration="0.5s" data-wow-delay="0.25s">
                    <form id="contact" action="<?php echo e(route('contact_us')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-lg-6">
                                <fieldset>
                                    <input type="text" name="name" id="name" placeholder="Nama" autocomplete="on" required>
                                </fieldset>
                            </div>
                            <div class="col-lg-6">
                                <fieldset>
                                    <input type="text" name="surname" id="surname" placeholder="Nama Belakang" autocomplete="on" required>
                                </fieldset>
                            </div>
                            <div class="col-lg-12">
                                <fieldset>
                                    <input type="email" name="email" id="email" placeholder="Email Kamu" required>
                                </fieldset>
                            </div>
                            <div class="col-lg-12">
                                <fieldset>
                                    <textarea name="message" class="form-control" id="message" placeholder="Pesan Kamu" required></textarea>
                                </fieldset>
                            </div>
                            <div class="col-lg-12">
                                <fieldset>
                                    <button type="submit" id="form-submit" class="main-button">Kirim Pesan</button>
                                </fieldset>
                            </div>
                        </div>
                        <div class="contact-dec">
                            <img src="<?php echo e(asset('templatemo_562_space_dynamic/assets/images/contact-decoration.png')); ?>" alt="">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 wow fadeIn text-center" data-wow-duration="1s" data-wow-delay="0.25s">
                    <!-- Social Media Icons -->
                    <div class="social-icons mb-4">
                        <a href="https://www.instagram.com/bpskotasurabaya/" target="_blank" class="social-link"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-whatsapp"></i></a>
                        <a href="bps3578@bps.go.id" class="social-link"><i class="bi bi-envelope-fill"></i></a>
                    </div>

                    <!-- Contact Information -->
                    <div class="contact-footer mb-3">
                        <p>Alamat: Jl. Ahmad Yani No.152E, Gayungan, Kec. Gayungan, Surabaya, Jawa Timur 60235</p>
                        <p>Telepon: 0318296692</p>
                        <p>© Copyright 2025 BPS Kota Surabaya. All Rights Reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('templatemo_562_space_dynamic/vendor/jquery/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('templatemo_562_space_dynamic/vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('templatemo_562_space_dynamic/assets/js/owl-carousel.js')); ?>"></script>
    <script src="<?php echo e(asset('templatemo_562_space_dynamic/assets/js/animation.js')); ?>"></script>
    <script src="<?php echo e(asset('templatemo_562_space_dynamic/assets/js/imagesloaded.js')); ?>"></script>
    <script src="<?php echo e(asset('templatemo_562_space_dynamic/assets/js/templatemo-custom.js')); ?>"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var btn = document.querySelector(".start-btn");
            if (btn) {
                setTimeout(function () {
                    btn.classList.add("animate-start-btn");
                }, 200);
            }
        });
    </script>
<?php $__env->stopPush(); ?>



<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\astabaya\resources\views/index.blade.php ENDPATH**/ ?>