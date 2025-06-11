<?php

return [
    'enable' => env('ENABLE_LOGIN_ATTEMPTS', true),
    'max_failed_attempts' => env('MAX_FAILED_LOGIN_ATTEMPTS', 5),
    'password_reset_days_enable' => env('PASSWORD_RESET_DAYS_ENABLE', true),
    'password_reset_days' => env('PASSWORD_RESET_DAYS', 60),
    'reset_user_on_first_login' => env('RESET_USER_ON_FIRST_LOGIN', true),
    'password_expiry_reminder_days' => env('PASSWORD_EXPIRY_REMINDER_DAYS', 5),
];
