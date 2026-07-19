<!DOCTYPE html>
<html>
<head>
    <title>Código de Verificación - JyM Barbería</title>
    <style>
        body { font-family: 'Montserrat', sans-serif; background-color: #050505; color: #ffffff; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #111111; padding: 30px; border-radius: 10px; text-align: center; }
        .logo { font-size: 40px; margin-bottom: 10px; }
        .brand { color: #D4AF37; font-size: 24px; font-weight: bold; letter-spacing: 2px; }
        .code { font-size: 48px; font-weight: 900; color: #D4AF37; letter-spacing: 5px; margin: 30px 0; padding: 15px; background: #000; display: inline-block; }
        .text { color: #aaaaaa; line-height: 1.6; }
        .footer { margin-top: 30px; font-size: 12px; color: #666; border-top: 1px solid #333; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">💈</div>
        <div class="brand">JYM BARBERÍA</div>
        
        <p class="text" style="margin-top: 20px;">Hola <strong>{{ $user->primer_nombre }}</strong>,</p>
        <p class="text">Hemos detectado un inicio de sesión en tu cuenta. Para continuar, por favor ingresa el siguiente código de verificación:</p>
        
        <div class="code">{{ $otp }}</div>
        
        <p class="text">Este código es válido por <strong>5 minutos</strong>.</p>
        
        <div class="footer">
            Si no solicitaste este código, por favor ignora este mensaje o contacta a soporte.<br>
            &copy; {{ date('Y') }} JyM Barbería & Perfumería.
        </div>
    </div>
</body>
</html>
