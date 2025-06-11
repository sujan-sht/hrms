<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Expiry Notification</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 0;
            margin: 0;
        }

        .container-sec {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 32px;
            margin: 32px auto;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
        }

        .header-section {
            background: linear-gradient(135deg, #f59e0b, #f97316);
            padding: 32px;
            border-radius: 8px;
            color: #ffffff;
            text-align: center;
            margin-bottom: 24px;
        }

        .header-section.expired {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .app-name {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .subtitle {
            font-family: monospace;
            font-size: 18px;
            margin-bottom: 16px;
        }

        .action-text {
            font-size: 20px;
            letter-spacing: 2px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .icon-large {
            font-size: 64px;
            margin-bottom: 24px;
            color: #fbbf24;
        }

        .icon-large.expired {
            color: #ef4444;
        }

        .icon-header {
            font-size: 32px;
        }

        .expiry-box {
            background-color: #fef3c7;
            border: 2px dashed #f59e0b;
            border-radius: 8px;
            padding: 24px;
            text-align: center;
            margin: 24px 0;
        }

        .expiry-box.expired {
            background-color: #fecaca;
            border-color: #ef4444;
        }

        .expiry-label {
            color: #92400e;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .expiry-label.expired {
            color: #991b1b;
        }

        .expiry-date {
            color: #78350f;
            font-size: 20px;
            font-weight: bold;
        }

        .expiry-date.expired {
            color: #7f1d1d;
        }

        .days-remaining {
            color: #a16207;
            font-size: 14px;
            margin-top: 8px;
        }

        .days-remaining.expired {
            color: #991b1b;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            display: inline-block;
            background-color: #f59e0b;
            color: #ffffff;
            font-weight: bold;
            padding: 16px 32px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 18px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
            margin: 24px 0;
        }

        .btn-primary:hover {
            background-color: #d97706;
        }

        .btn-primary.expired {
            background-color: #ef4444;
        }

        .btn-primary.expired:hover {
            background-color: #dc2626;
        }

        .btn-secondary {
            display: inline-block;
            background-color: #3b82f6;
            color: #ffffff;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #2563eb;
        }

        .info-box {
            background-color: #dbeafe;
            border-left: 4px solid #3b82f6;
            padding: 16px;
            margin: 24px 0;
            border-radius: 0 8px 8px 0;
        }

        .info-box.warning {
            background-color: #fef3c7;
            border-left-color: #f59e0b;
        }

        .info-box.notice {
            background-color: #f3f4f6;
            border-left-color: #6b7280;
        }

        .info-title {
            color: #1e40af;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 16px;
        }

        .info-title.warning {
            color: #92400e;
        }

        .info-title.notice {
            color: #374151;
        }

        .info-list {
            color: #1d4ed8;
            font-size: 14px;
            margin: 0;
            padding-left: 0;
            list-style: none;
        }

        .info-list.warning {
            color: #a16207;
        }

        .info-list.notice {
            color: #4b5563;
        }

        .info-list li {
            margin-bottom: 4px;
        }

        .support-box {
            background-color: #dbeafe;
            border-radius: 8px;
            padding: 16px;
            margin: 24px 0;
            text-align: center;
        }

        .support-title {
            color: #1e40af;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .support-text {
            color: #1d4ed8;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .footer-text {
            color: #6b7280;
            font-size: 14px;
            text-align: center;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
        }

        .footer-text a {
            color: #3b82f6;
            text-decoration: none;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>


    <!-- PASSWORD EXPIRED NOTIFICATION -->
    <div class="container-sec">
        <div style="text-align: center;">
            <!-- Alert Icon -->
            <div style="margin-bottom: 24px;">
                <i class="fas fa-times-circle icon-large expired"></i>
            </div>

            <!-- Header Section -->
            <div class="header-section expired">
                <div class="app-name">
                    {{ env('APP_NAME') }}
                </div>
                <div class="subtitle">
                    Password Has Expired
                </div>
                <div class="action-text">
                    Immediate Action Required
                </div>
                <div>
                    <i class="fas fa-ban icon-header"></i>
                </div>
            </div>

            <!-- Content -->
            <h2 style="font-size: 24px; font-weight: 600; color: #1f2937; margin-bottom: 16px;">Hello,
                {{ $name }}</h2>
            <p style="color: #6b7280; margin-bottom: 24px; font-size: 16px;">Your password has expired and your account
                access has been suspended for security reasons. Please reset your password immediately to regain access.
            </p>

            <!-- Expiry Details -->
            <div class="expiry-box expired">
                <div class="expiry-label expired">Password Expired On:</div>
                <div class="expiry-date expired">{{ $expired_at }}</div>
                <div class="days-remaining expired">
                    <i class="fas fa-lock"></i>
                    <span>Account access is currently restricted</span>
                </div>
            </div>

            <!-- Action Button -->
            <div style="margin: 24px 0;">
                <a href="{{ $reset_password_url }}" class="btn-primary expired">
                    <i class="fas fa-redo" style="margin-right: 8px;"></i>
                    Reset Password Now
                </a>
            </div>

            <!-- Important Notice -->
            <div class="info-box notice">
                <div class="info-title notice">
                    <i class="fas fa-info-circle" style="margin-right: 8px;"></i>
                    Important Notice:
                </div>
                <div style="color: #4b5563; font-size: 14px;">
                    <p style="margin: 8px 0;">• Your account will remain locked until you reset your password</p>
                    <p style="margin: 8px 0;">• All active sessions have been terminated for security</p>
                    <p style="margin: 8px 0;">• You will need to log in again after resetting your password</p>
                    <p style="margin: 8px 0;">• If you need assistance, contact our support team</p>
                </div>
            </div>

            <!-- Alternative Access -->
            <div class="support-box">
                <div class="support-title">Need Help?</div>
                <p class="support-text">If you're unable to reset your password using the link above:</p>
                <a href="{{ $support_url }}" class="btn-secondary">
                    <i class="fas fa-headset" style="margin-right: 8px;"></i>
                    Contact Support
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-text">
            <p>This is an automated security notification. If you believe this is an error, please <a
                    href="#">contact our support team</a> immediately.</p>
            <p style="margin-top: 8px;">Thank you,<br>The {{ env('APP_NAME') }} Team</p>
        </div>
    </div>

</body>

</html>
