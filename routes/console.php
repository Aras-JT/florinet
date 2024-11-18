<?php

use Illuminate\Support\Facades\Schedule;
use App\Jobs\RetrieveProductsJob;

Schedule::job(new RetrieveProductsJob, 'products')
->name('RetrieveProductsJob')->dailyAt('01:00');