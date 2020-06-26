<?php

namespace App\Http\Controllers;

class SpaController extends Controller
{

    // Single Action Controller: https://laravel.com/docs/7.x/controllers#single-action-controllers
    public function __invoke()
    {
        return view('spa');
    }

}
