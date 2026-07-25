<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JyM Barbería - Acceso</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; }

        body {
            background: #000000;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .login-card {
            background: #111111;
            width: 100%;
            max-width: 420px;
            padding: 2.2rem 2.5rem;
            box-shadow: 0 0 0 1px #2a2a2a, 0 30px 60px rgba(0,0,0,0.8);
        }

        .logo-wrap {
            width: 60px;
            height: 60px;
            background: #D4AF37;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.68rem;
            font-weight: 700;
            color: #555555;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.8rem 1rem;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            color: #f1f1f1;
            background: #1c1c1c;
            border: none;
            border-bottom: 2px solid #2a2a2a;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-input:focus { border-bottom-color: #D4AF37; }
        .form-input::placeholder { color: #3a3a3a; }

        .gold-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #D4AF37, transparent);
            opacity: 0.5;
            margin: 1.25rem 0;
        }

        .btn-submit {
            width: 100%;
            padding: 0.9rem;
            background: #D4AF37;
            color: #000000;
            font-family: 'Inter', sans-serif;
            font-size: 0.78rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            border: none;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }

        .btn-submit:hover { background: #c9a430; }
        .btn-submit:active { transform: scale(0.99); }
    </style>
</head>
<body>
    <div class="login-card">
        {{ $slot }}
    </div>
</body>
</html>