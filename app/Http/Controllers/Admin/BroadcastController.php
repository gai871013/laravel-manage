<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BroadcastController extends Controller
{
    public function getIndex(Request $request)
    {
        return view('admin.WeChat.broadcast.index');
    }
}
