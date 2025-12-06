<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the person likes threshold check to run daily at midnight
Schedule::command('check:person-likes-threshold')
    ->daily()
    ->at('00:00')
    ->timezone('Asia/Jakarta')
    ->emailOutputOnFailure(config('mail.admin_email', 'admin@example.com'));
