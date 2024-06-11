<?php

namespace App\Http\Controllers\Web;

use App\Helpers\Customer\Angular;
use App\Http\Controllers\Controller;

class AppController extends Controller
{
    /**
     * Our custom service provider is going to make sure
     * $ng is a singleton
     */
    public function index(Angular $ng)
    {
        // Provide our service's assets as $ngAssets inside
        // of app.blade.php
        return view('app', ['ngAssets' => $ng->assets]);
    }

    public function log()
    {
        // Provide our service's assets as $ngAssets inside
        // of app.blade.php
        return view('welcome');
    }
}
