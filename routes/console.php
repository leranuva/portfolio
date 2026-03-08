<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Para Hostinger: procesar cola cada minuto vía cron (evita worker permanente)
Schedule::command('queue:work --stop-when-empty --max-time=50')->everyMinute();
