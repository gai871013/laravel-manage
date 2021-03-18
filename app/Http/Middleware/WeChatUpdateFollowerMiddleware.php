<?php
/**
 * Created by PhpStorm.
 * User: @gai871013
 * Date: 2017-05-11
 * Time: 09:32
 */

namespace App\Http\Middleware;

use App\Models\Follower;
use Closure;

class WeChatUpdateFollowerMiddleware
{

    public function handle($request, Closure $next)
    {
        $from = (int)$request->input('id');
        $follower = Follower::where('id', $from)->first();
        $user_wx = session('wechat.oauth_user');
//        dd($user_wx);
        $openId = $user_wx['original']['openid'];
        $user = Follower::where('openid', $openId)->lockForUpdate()->first();
        // 服务器获取用户信息
//        $app = new Application(config('wechat'));
//        $user_original = $app->user->get($user_wx['id']);
        $user_original = $user_wx['original'];
        // 未关注，跳转到首页
        /*if ($user_original['subscribe'] == 0) {
            return redirect()->route('weChat.notFollow');
        }*/
        if (empty($user)) {
            $user = new Follower();
        }

        $user->openid = $user_wx['id'];
        $user->subscribe = $user_original['subscribe'] ?? 0;
        $user->nickname = $user_original['nickname'] ?? '';
        $user->sex = $user_original['sex'] ?? '';
        $user->language = $user_original['language'] ?? '';
        $user->city = $user_original['city'] ?? '';
        $user->province = $user_original['province'] ?? '';
        $user->country = $user_original['country'] ?? '';
        $user->headimgurl = $user_original['headimgurl'] ?? '';
        $user->subscribe_time = isset($user_original['subscribe_time']) ? $user_original['subscribe_time'] : 0;
        $user->remark = isset($user_original['remark']) ? $user_original['remark'] : '';
        $user->groupid = isset($user_original['groupid']) ? $user_original['groupid'] : 0;
        $user->save();

        return $next($request);
    }
}