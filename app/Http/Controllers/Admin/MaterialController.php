<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MaterialController extends Controller
{

    /**
     * 永久素材列表 2017-8-7 11:23:32 by gai871013
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getForeverLists(Request $request)
    {
        return view('admin.WeChat.material.foreverLists');
    }

    /**
     * 临时素材列表 2017-8-7 11:24:26 by gai871013
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTemporaryLists(Request $request)
    {
        return view('admin.WeChat.material.temporaryLists');
    }
}
