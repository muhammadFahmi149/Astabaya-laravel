<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Baru dari Form Kontak</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #234C6A;
            color: #ffffff;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            margin: -30px -30px 20px -30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin: 20px 0;
        }
        .info-box {
            background-color: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #234C6A;
            margin: 20px 0;
        }
        .info-box strong {
            color: #234C6A;
        }
        .message-box {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            white-space: pre-wrap;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pesan Baru dari Form Kontak Aastabaya</h1>
        </div>
        
        <div class="content">
            <p>Anda menerima pesan baru dari form kontak website Aastabaya:</p>
            
            <div class="info-box">
                <p><strong>Nama:</strong> {{ $fullName }}</p>
                <p><strong>Email:</strong> <a href="mailto:{{ $email }}">{{ $email }}</a></p>
            </div>
            
            <h3 style="color: #234C6A; margin-top: 30px;">Pesan:</h3>
            <div class="message-box">
                {{ $message }}
            </div>
        </div>
        
        <div class="footer">
            <p>Email ini dikirim otomatis dari sistem Aastabaya - BPS Kota Surabaya</p>
            <p>Anda dapat membalas email ini langsung ke: <a href="mailto:{{ $email }}">{{ $email }}</a></p>
        </div>
    </div>
</body>
</html>

