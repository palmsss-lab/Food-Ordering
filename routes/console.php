<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Auto-expire promotions whose end_date has passed
Schedule::call(function () {
    \App\Models\Promotion::where('is_active', true)
        ->where('end_date', '<', \Illuminate\Support\Carbon::today()->toDateString())
        ->update(['is_active' => false]);
})->daily();
