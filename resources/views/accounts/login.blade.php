@extends('layouts.app')

@section('title', 'Login')

@push('styles')
    <style>
        body {
            font-family: "Poppins", sans-serif;
            text-align: center;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url("{{ asset('images/img/background-login-register.jpg') }}") no-repeat center center;
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .container {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            border-radius: 24px;
            padding: 30px 35px 30px 35px;
            width: 380px;
            max-width: 90%;
            min-height: auto;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2), 0 0 0 1px rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.18);
            display: flex;
            flex-direction: column;
            align-items: center;
            box-sizing: border-box;
            margin: auto;
        }

        h2 {
            font-size: 25px;
            font-family: sans-serif;
            font-weight: 600;
            color: #fff;
            margin-bottom: 5px;
            margin-top: 8px;
            text-align: center;
            letter-spacing: -0.5px;
            width: 100%;
        }

        h3 {
            font-size: 15px;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
            font-weight: 400;
            color: #fff;
            margin-bottom: 20px;
            margin-top: 0;
            text-align: center;
            letter-spacing: -0.5px;
            width: 100%;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        input {
            width: 100%;
            padding: 12px 16px;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 15px;
            transition: all 0.3s ease;
            outline: none;
            box-sizing: border-box;
        }

        input::placeholder {
            color: rgb(240, 251, 255);
        }

        button {
            width: 100%;
            padding: 11px;
            background: linear-gradient(135deg, #258ffa 0%, #1c7dd8 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            box-sizing: border-box;
        }

        button:hover {
            transform: scale(1.05);
            background: linear-gradient(90deg, #ff4b2b, #ff416c);
        }

        img {
            border-radius: 20px;
            width: 150px;
            height: auto;
            margin-bottom: 5px;
            margin-top: 0;
        }

        .register-link {
            text-align: center;
            margin-top: 15px;
            margin-bottom: 0;
            font-size: 14px;
            color: #d0e1f0;
            width: 100%;
        }

        .register-link a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
            color: #ff4b2b;
        }

        #login-error {
            width: 100%;
            margin: 5px 0 10px 0;
            text-align: center;
            box-sizing: border-box;
            color: #ff6b6b;
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <img src="{{ asset('images/logoastabayav2.png') }}" alt="Logo Astabaya" width="150">
        <h2>Selamat Datang</h2>
        <h3>Masuk ke akun anda</h3>
        <p id="login-error" class="error-message"></p>
        <form id="login-form" method="POST" action="{{ route('login-form') }}">
            @csrf
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Masuk</button>
        </form>

        <div class="text-center" style="width: 100%; margin: 12px 0 0 0;">
            <p style="color: rgba(255, 255, 255, 0.7); margin: 10px 0;">atau</p>
            <button type="button" onclick="signInWithGoogle()" style="width: 100%; padding: 10px; border: 1px solid #dadce0; background: #fff; color: #3c4043; font-size: 14px; font-weight: 500; border-radius: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; box-sizing: border-box;">
                <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg">
                    <g fill="#000" fill-rule="evenodd">
                        <path d="M9 3.48c1.69 0 2.83.73 3.48 1.34l2.54-2.48C13.46.89 11.43 0 9 0 5.48 0 2.44 2.02.96 4.96l2.91 2.26C4.6 5.05 6.62 3.48 9 3.48z" fill="#EA4335"/>
                        <path d="M17.64 9.2c0-.74-.06-1.28-.19-1.84H9v3.34h4.96c-.21 1.18-.84 2.18-1.79 2.85l2.75 2.13c1.66-1.52 2.72-3.76 2.72-6.48z" fill="#4285F4"/>
                        <path d="M3.88 10.78A5.54 5.54 0 0 1 3.58 9c0-.62.11-1.22.29-1.78L.96 4.96A9.008 9.008 0 0 0 0 9c0 1.45.35 2.82.96 4.04l2.92-2.26z" fill="#FBBC05"/>
                        <path d="M9 18c2.43 0 4.47-.8 5.96-2.18l-2.75-2.13c-.76.53-1.78.9-3.21.9-2.38 0-4.4-1.57-5.12-3.74L.96 13.04C2.45 15.98 5.48 18 9 18z" fill="#34A853"/>
                    </g>
                </svg>
                Masuk dengan Google
            </button>
        </div>
        <div class="register-link">Belum memiliki akun? <a href="{{ route('signup') }}">Daftar</a></div>
    </div>
@endsection

@push('scripts')
    <script>
        function signInWithGoogle() {
            window.location.href = "{{ route('google.login') }}";
        }
    </script>
@endpush


