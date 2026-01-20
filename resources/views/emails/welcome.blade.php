<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to Student Management System</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; }
        .container { max-width: 600px; margin: 20px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { background-color: #4CAF50; color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
        .content { line-height: 1.6; color: #333; }
        .footer { margin-top: 20px; color: #666; font-size: 12px; border-top: 1px solid #eee; padding-top: 15px; }
        .btn { display: inline-block; background-color: #4CAF50; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🎓 Welcome to Student Management System</h2>
        </div>
        <div class="content">
            <p>Hi {{ $user->name }},</p>
            <p>Welcome! Your account has been successfully created.</p>
            
            <div style="background-color: #f5f5f5; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <p><strong>Account Details:</strong></p>
                <p>Email: {{ $user->email }}</p>
                <p>Role: <strong>{{ ucfirst($user->role) }}</strong></p>
            </div>

            <p>You can now log in to your dashboard and start using the system.</p>
            <a href="{{ url('/login') }}" class="btn">Go to Login</a>
        </div>
        <div class="footer">
            <p>This is an automated message. Please do not reply.</p>
            <p>&copy; {{ date('Y') }} Student Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
