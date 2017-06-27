<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth.admin:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = '管理后台首页';
        /*Log::emergency($title);
        Log::alert($title);
        Log::critical($title);
        Log::error($title);
        Log::warning($title);
        Log::notice($title);
        Log::info($title);
        Log::debug($title);*/
        $user = auth('admin')->user();
        return view('admin.home', compact('title', 'user'));
//        var_dump($request->session()->all());
//        dd('后台首页，当前用户名：'.auth('admin')->user()->username);
    }
}