<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Person Likes Threshold Reached</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header .emoji {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px 20px;
            color: #333333;
        }
        .content p {
            font-size: 16px;
            line-height: 1.6;
            margin: 10px 0;
        }
        .highlight {
            background-color: #f0f4ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .highlight strong {
            color: #667eea;
            font-size: 18px;
        }
        .info-box {
            background-color: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #666666;
        }
        .info-value {
            color: #333333;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            color: #888888;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .button:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="emoji">🎉</div>
            <h1>Person Likes Threshold Reached!</h1>
        </div>
        
        <div class="content">
            <p>Hello Admin,</p>
            
            <div class="highlight">
                <strong>{{ $personName }}</strong> has reached the milestone of <strong>{{ $likesCount }} likes</strong>!
            </div>
            
            <p>This person has become very popular on the platform. Here are the details:</p>
            
            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Person ID:</span>
                    <span class="info-value">#{{ $personId }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Name:</span>
                    <span class="info-value">{{ $personName }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Age:</span>
                    <span class="info-value">{{ $personAge }} years old</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Location:</span>
                    <span class="info-value">{{ $personLocation }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total Likes:</span>
                    <span class="info-value"><strong>{{ $likesCount }}</strong></span>
                </div>
            </div>
            
            <p>This is an automated notification to keep you informed about popular profiles on your platform.</p>
            
            <p style="text-align: center; margin-top: 30px;">
                <em>Keep up the great work!</em>
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} HyperHire. All rights reserved.</p>
            <p>This is an automated email. Please do not reply.</p>
        </div>
    </div>
</body>
</html>
