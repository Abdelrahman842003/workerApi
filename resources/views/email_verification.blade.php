<!-- resources/views/email_verification.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
    <style>
        /* Inline CSS for email styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333333;
        }

        p {
            font-size: 16px;
            line-height: 1.5;
            color: #666666;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #28a745;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #218838;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #999999;
        }
    </style>
</head>
<body>
<div class="email-container">
    <h1>Hello, {{ $name }}</h1>
    <p>Thank you for registering with us! Please verify your email address by clicking the button below:</p>

    <a href="{{$link}}" class="btn">{{$link}}</a>

    <p>If you didn’t create an account, you can safely ignore this email.</p>

    <div class="footer">
        <p>Best regards,<br>Your Company</p>
    </div>
</div>
</body>
</html>
