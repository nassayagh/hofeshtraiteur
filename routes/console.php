<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;
use \App\Models\Prestation;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();
Schedule::call(function () {
    Prestation::where("status","=",Prestation::VALIDATED)->where("event_date", "<", \Carbon\Carbon::now())->update(["status" => Prestation::CLOSED]);
})->dailyAt('00:00');
