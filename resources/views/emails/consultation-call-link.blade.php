<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #667eea, #764ba2); padding: 36px 40px; text-align: center; color: white; }
        .header h1 { margin: 0; font-size: 22px; font-weight: 700; }
        .header p { margin: 8px 0 0; opacity: 0.85; font-size: 14px; }
        .body { padding: 36px 40px; }
        .detail-box { background: #f8f9ff; border-left: 4px solid #667eea; border-radius: 6px; padding: 16px 20px; margin: 20px 0; }
        .detail-box p { margin: 6px 0; font-size: 14px; color: #444; }
        .detail-box strong { color: #333; }
        .cta-btn { display: block; text-align: center; background: #667eea; color: white; text-decoration: none; padding: 14px 32px; border-radius: 50px; font-weight: 700; font-size: 16px; margin: 28px 0; }
        .message-box { background: #fffbf0; border: 1px solid #ffe082; border-radius: 8px; padding: 14px 18px; margin: 20px 0; font-size: 14px; color: #555; }
        .footer { background: #f4f6f8; padding: 20px 40px; text-align: center; font-size: 12px; color: #999; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>📅 Your Consultation is Confirmed</h1>
        <p>A call link has been sent to you by {{ $academicName }}</p>
    </div>
    <div class="body">
        <p>Hi <strong>{{ $studentName }}</strong>,</p>
        <p>Your consultation session is confirmed. Please use the link below to join the call at the scheduled time.</p>

        <div class="detail-box">
            <p><strong>📆 Date:</strong> {{ $bookingDate }}</p>
            <p><strong>⏰ Time:</strong> {{ $bookingTime }}</p>
            <p><strong>👤 With:</strong> {{ $academicName }}</p>
        </div>

        <a href="{{ $callLink }}" class="cta-btn">Join the Call</a>

        @if($extraMessage)
        <div class="message-box">
            <strong>Message from {{ $academicName }}:</strong><br>
            {{ $extraMessage }}
        </div>
        @endif

        <p style="font-size:13px;color:#888;">If the button above doesn't work, copy and paste this link into your browser:<br>
        <a href="{{ $callLink }}" style="color:#667eea;word-break:break-all;">{{ $callLink }}</a></p>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} AcadSuite. This email was sent on behalf of {{ $academicName }}.
    </div>
</div>
</body>
</html>
