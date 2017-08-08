<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * 自动回复管理 2017-8-7 11:27:43 by gai871013
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex(Request $request)
    {
        return view('admin.WeChat.message.index');
    }

    /**
     * 关注回复 2017-8-8 11:21:21 by gai871013
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSubscribe(Request $request)
    {
        return view('admin.WeChat.message.subscribe');
    }
}
