<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Configuration
|--------------------------------------------------------------------------
*/

uses(
    TestCase::class,
    RefreshDatabase::class
)->in('Feature');
