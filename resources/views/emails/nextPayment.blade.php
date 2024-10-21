<!DOCTYPE html>
<html>
<head>
    <title>Recordatorio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333333;
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            color: #555555;
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            color: #999999;
            font-size: 12px;
            margin-top: 20px;
        }
        .footer img {
            width: 100px;
            margin-top: 10px;
        }
        @media (max-width: 600px) {
            .container {
                padding: 10px;
            }
            h1 {
                font-size: 20px;
            }
            p {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Hola, {{ $subscription->user->name }}</h1>
    <p>Este es un recordatorio de que tu próximo cobro será mañana, {{ $subscription->next_billing_date }}.</p>
    <p>El monto a pagar será: {{ $subscription->suscriptionPlan->amount }} {{ $subscription->initialPayment->currency->code }}</p>
    <p>Gracias por tu preferencia.</p>
    <div class="footer">
        <p>© 2024 Tu Empresa. Todos los derechos reservados.</p>
        <img src="https://static.placetopay.com/placetopay-logo.svg" alt="Logo">
    </div>
</div>
</body>
</html>
