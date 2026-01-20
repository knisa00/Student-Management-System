<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registration Approved</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; }
        .container { max-width: 600px; margin: 20px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { background-color: #E3F2FD; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .content { line-height: 1.6; }
        .footer { margin-top: 20px; color: #666; font-size: 12px; }
        .btn { display: inline-block; background-color: #E3F2FD; color: #263238; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🎓 Student Management System</h2>
        </div>
        <div class="content">
            <p>Hi Student,</p>
            <p>Your registration for the following course has been <strong>approved</strong>:</p>
            
            <div style="background-color: #f5f5f5; padding: 15px; border-radius: 8px; margin: 15px 0;">
                <p><strong>Course Code:</strong> {{ $course->course_code }}</p>
                <p><strong>Course Title:</strong> {{ $course->title }}</p>
                <p><strong>Semester:</strong> {{ $course->semester }}</p>
            </div>

            <p>You can view your registered courses in your dashboard.</p>
            <a href="{{ url('/student/dashboard') }}" class="btn">Go to Dashboard</a>
        </div>
        <div class="footer">
            <p>This is an automated message. Please do not reply.</p>
            <p>&copy; {{ date('Y') }} Student Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>