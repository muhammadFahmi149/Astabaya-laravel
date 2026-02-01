<?php $__env->startSection('title', 'Sign Up'); ?>

<?php $__env->startPush('styles'); ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: "Poppins", sans-serif;
            text-align: center;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url("<?php echo e(asset('images/img/background-login-register.jpg')); ?>") no-repeat center center;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-x: hidden;
            margin: 0;
        }

        .custom-container {
            position: relative;
            z-index: 1;
            background: rgba(225, 224, 224, 0.08);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 24px;
            padding: 25px 40px;
            padding-top: 30px;
            width: 100%;
            max-width: 350px;
            height: auto;
            min-height: 420px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.37), inset 0 1px 2px 0 rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            text-align: center;
        }

        h2 {
            font-size: 25px;
            font-family: sans-serif;
            font-weight: 600;
            color: #fff;
            margin-bottom: 0px;
            text-align: center;
            letter-spacing: -0.5px;
        }

        h3 {
            font-size: 15px;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
            font-weight: 400;
            color: #fff;
            margin-bottom: 20px;
            margin-top: 7px;
            text-align: center;
            letter-spacing: -0.5px;
        }

        .form-control-custom {
            width: 100%;
            padding: 8px 16px;
            font-family: "Poppins", system-ui, -apple-system, sans-serif;
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            color: #fff;
            font-size: 15px;
            transition: all 0.3s ease;
            outline: none;
            box-sizing: border-box;
        }

        .form-control-custom::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control-custom:focus {
            border: 1px solid rgba(255, 255, 255, 0.4);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
        }

        .text-start {
            width: 100%;
            text-align: left;
            margin-bottom: 0;
        }

        .btn-signup-custom {
            width: 100%;
            padding: 8px;
            background: linear-gradient(135deg, #258ffa 0%, #1c7dd8 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            justify-content: center;
            font-family: Arial, Helvetica, sans-serif;
        }

        .btn-signup-custom:hover {
            transform: scale(1.05);
            background: linear-gradient(90deg, #ff4b2b, #ff416c);
        }

        img {
            border-radius: 0;
            margin-bottom: 8px;
            max-width: 160px;
            height: auto;
        }

        .login-section {
            margin-top: 0px;
            padding-top: 0px;
        }

        .login-section p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 0;
        }

        #signup-form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .error-message {
            color: #ff6b6b;
            font-size: 13px;
            margin-top: 8px;
            display: none;
            text-align: left;
            padding: 8px 12px;
            background: rgba(255, 107, 107, 0.1);
            border-radius: 8px;
            border-left: 3px solid #ff6b6b;
        }

        .btn-login-custom {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-login-custom:hover {
            text-shadow: 0 0 20px rgba(6, 182, 212, 0.5);
            text-decoration: none;
        }

        /* Loading Animation */
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .loading-overlay.active {
            display: flex;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-top: 5px solid #258ffa;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-text {
            color: white;
            margin-top: 20px;
            font-size: 16px;
            font-weight: 500;
        }

        .loading-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Success Modal */
        .success-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 10000;
            justify-content: center;
            align-items: center;
        }

        .success-modal.active {
            display: flex;
        }

        .success-modal-content {
            background: rgba(225, 224, 224, 0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 24px;
            padding: 40px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.37);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .success-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: scaleIn 0.3s ease-out;
        }

        @keyframes scaleIn {
            0% {
                transform: scale(0);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        .success-icon svg {
            width: 50px;
            height: 50px;
            color: white;
        }

        .success-modal h3 {
            color: #1f2937;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .success-modal p {
            color: #6b7280;
            font-size: 16px;
            margin-bottom: 30px;
        }

        .success-modal-btn {
            background: linear-gradient(135deg, #258ffa 0%, #1c7dd8 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .success-modal-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(37, 143, 250, 0.4);
        }

        .btn-signup-custom:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="custom-container w-100">
        <img src="<?php echo e(asset('images/logoastabayav2.png')); ?>" alt="Logo Astabaya" width="150">
        <h2>Selamat Datang</h2>
        <h3>Buat akun anda</h3>

        <form id="signup-form" class="d-flex flex-column gap-3">
            <?php echo csrf_field(); ?>
            <div class="text-start">
                <label for="username" class="form-label visually-hidden">Username</label>
                <input type="text" name="username" placeholder="Username" required class="form-control form-control-custom">
            </div>

            <div class="text-start">
                <label for="email" class="form-label visually-hidden">Email</label>
                <input type="email" name="email" placeholder="Email" required class="form-control form-control-custom">
            </div>

            <div class="text-start">
                <label for="password" class="form-label visually-hidden">Password</label>
                <input type="password" name="password" placeholder="Password" required class="form-control form-control-custom">
            </div>

            <p id="password-error" class="error-message"></p>

            <button type="submit" class="btn-signup-custom">Daftar</button>

            <div class="text-center" style="margin: 0;">
                <p style="color: rgba(255, 255, 255, 0.7); margin: 0 0 10px 0;">atau</p>
                <button type="button" onclick="signInWithGoogle()" style="width: 100%; padding: 10px; border: 1px solid #dadce0; background: #fff; color: #3c4043; font-size: 14px; font-weight: 500; border-radius: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; box-sizing: border-box;">
                    <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg">
                        <g fill="#000" fill-rule="evenodd">
                            <path d="M9 3.48c1.69 0 2.83.73 3.48 1.34l2.54-2.48C13.46.89 11.43 0 9 0 5.48 0 2.44 2.02.96 4.96l2.91 2.26C4.6 5.05 6.62 3.48 9 3.48z" fill="#EA4335"/>
                            <path d="M17.64 9.2c0-.74-.06-1.28-.19-1.84H9v3.34h4.96c-.21 1.18-.84 2.18-1.79 2.85l2.75 2.13c1.66-1.52 2.72-3.76 2.72-6.48z" fill="#4285F4"/>
                            <path d="M3.88 10.78A5.54 5.54 0 0 1 3.58 9c0-.62.11-1.22.29-1.78L.96 4.96A9.008 9.008 0 0 0 0 9c0 1.45.35 2.82.96 4.04l2.92-2.26z" fill="#FBBC05"/>
                            <path d="M9 18c2.43 0 4.47-.8 5.96-2.18l-2.75-2.13c-.76.53-1.78.9-3.21.9-2.38 0-4.4-1.57-5.12-3.74L.96 13.04C2.45 15.98 5.48 18 9 18z" fill="#34A853"/>
                        </g>
                    </svg>
                    Daftar dengan Google
                </button>
            </div>
            <div class="login-section">
                <p class="mb-2">Sudah Memiliki Akun? <a href="<?php echo e(route('login')); ?>" class="btn-login-custom">Masuk</a></p>
            </div>
        </form>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="loading-overlay">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <div class="loading-text">Memproses pendaftaran...</div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="success-modal" class="success-modal">
        <div class="success-modal-content">
            <div class="success-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3>Pendaftaran Berhasil!</h3>
            <p>Akun Anda telah berhasil dibuat. Silakan masuk untuk melanjutkan.</p>
            <button class="success-modal-btn" onclick="closeSuccessModal()">Masuk Sekarang</button>
        </div>
    </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function signInWithGoogle() {
            window.location.href = "<?php echo e(route('google.login')); ?>";
        }

        // Show loading overlay
        function showLoading() {
            document.getElementById('loading-overlay').classList.add('active');
            const submitBtn = document.querySelector('.btn-signup-custom');
            submitBtn.disabled = true;
        }

        // Hide loading overlay
        function hideLoading() {
            document.getElementById('loading-overlay').classList.remove('active');
            const submitBtn = document.querySelector('.btn-signup-custom');
            submitBtn.disabled = false;
        }

        // Show success modal
        function showSuccessModal() {
            document.getElementById('success-modal').classList.add('active');
        }

        // Close success modal and redirect
        function closeSuccessModal() {
            document.getElementById('success-modal').classList.remove('active');
            window.location.href = "<?php echo e(route('login')); ?>";
        }

        document.getElementById("signup-form").addEventListener("submit", async function (event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const passwordError = document.getElementById("password-error");
            passwordError.style.display = "none";

            // Show loading
            showLoading();

            try {
                // Use web route instead of API route to avoid token creation for web requests
                const response = await fetch("<?php echo e(route('register')); ?>", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: formData,
                });

                const responseData = await response.json();

                // Hide loading
                hideLoading();

                if (response.ok) {
                    // Show success modal instead of alert
                    showSuccessModal();
                } else {
                    // Extract detailed error messages
                    let errorMessage = responseData.message || 'Terjadi kesalahan';
                    
                    // If there are validation errors, show them
                    if (responseData.errors) {
                        const errorMessages = [];
                        for (const field in responseData.errors) {
                            if (responseData.errors[field]) {
                                errorMessages.push(...responseData.errors[field]);
                            }
                        }
                        if (errorMessages.length > 0) {
                            errorMessage = errorMessages.join(', ');
                        }
                    }
                    
                    passwordError.textContent = `⚠️ ${errorMessage}`;
                    passwordError.style.display = "block";
                }
            } catch (error) {
                console.error("Error:", error);
                hideLoading();
                passwordError.textContent = "⚠️ Terjadi kesalahan yang tidak terduga. Silakan coba lagi.";
                passwordError.style.display = "block";
            }
        });
    </script>
<?php $__env->stopPush(); ?>



<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\astabaya\resources\views/accounts/signup.blade.php ENDPATH**/ ?>