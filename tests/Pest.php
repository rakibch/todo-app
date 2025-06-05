<?php

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase; 

// beforeEach(function () {
//     Artisan::call('migrate:fresh');
//     Artisan::call('passport:install', ['--force' => true]);
// });

uses(TestCase::class)->in('Feature');
