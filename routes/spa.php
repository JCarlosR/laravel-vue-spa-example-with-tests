<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SPA Routes
|--------------------------------------------------------------------------
*/

Route::get('{path}', 'SpaController')->where('path', '(.*)');
