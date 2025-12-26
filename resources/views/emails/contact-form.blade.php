<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo mensaje del portfolio</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-top: none;
        }
        .field {
            margin-bottom: 20px;
        }
        .label {
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 5px;
            display: block;
        }
        .value {
            background: white;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }
        .message-box {
            background: white;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            white-space: pre-wrap;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">Nuevo Mensaje del Portfolio</h1>
    </div>
    
    <div class="content">
        <div class="field">
            <span class="label">Nombre:</span>
            <div class="value">{{ $name }}</div>
        </div>
        
        <div class="field">
            <span class="label">Email:</span>
            <div class="value">
                <a href="mailto:{{ $email }}" style="color: #667eea; text-decoration: none;">{{ $email }}</a>
            </div>
        </div>
        
        <div class="field">
            <span class="label">Asunto:</span>
            <div class="value">{{ $subject }}</div>
        </div>
        
        <div class="field">
            <span class="label">Mensaje:</span>
            <div class="message-box">{{ $message }}</div>
        </div>
    </div>
    
    <div class="footer">
        <p>Este mensaje fue enviado desde el formulario de contacto del portfolio.</p>
        <p>Puedes responder directamente a este email para contactar con {{ $name }}.</p>
    </div>
</body>
</html>
