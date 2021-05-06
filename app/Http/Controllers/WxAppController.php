<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WxAppController extends Controller
{
    public function index()
    {
        return view('wx-app.index');
    }
}
