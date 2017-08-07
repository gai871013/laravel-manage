<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Follower;
use App\Models\Journey;
use App\Models\Task;
use Illuminate\Http\Request;

class WeChatController extends Controller
{
    private $user;

    /**
     * 构造函数 2017-5-10 17:31:32 by gai871013
     * WeChatController constructor.
     */
    public function __construct()
    {
        $this->middleware('weChat.updateFollower')->except('getIndex');
    }


    /**
     * 微信端首页 2017-5-11 12:07:11 by gai871013
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex(Request $request)
    {
        $follower = $this->getFollower();
        return view('wechat.index', compact('follower'));
    }

    /**
     * 获取用户微信信息 2017-5-11 12:08:09 by gai871013
     * @return mixed
     */
    private function getFollower()
    {
        $follower = session('wechat.oauth_user');
        $follower = Follower::where('openid', $follower['id'])->first();
        return $follower;
    }


    /**
     * 绑定用户 2017-5-11 09:43:14 by gai871013
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getBindUser(Request $request)
    {
        $follower = $this->getFollower();
        if ($follower->user) {
            return redirect()->route('weChat.home');
        }
        return view('wechat.bindUser', compact('follower'));
    }
}
