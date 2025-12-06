<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::exec('php ' . base_path('app/scheduleScripts/autoImportDate.php'))
    ->twiceDaily(3, 12)
    ->appendOutputTo(storage_path('logs/importSchedule.log'));
