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

class WeChatBindUserMiddleware
{

    public function handle($request, Closure $next)
    {
        $user_wx = session('wechat.oauth_user');
        $openId = $user_wx['original']['openid'];
        $user = Follower::where('openid', $openId)->lockForUpdate()->first();
        $user_original = $user_wx['original'];
        if (empty($user)) {
            $user = new Follower();
            $user->subscribe = 1;
            $user->openid = $user_original['openid'];
            $user->subscribe = isset($user_original['subscribe']) ? $user_original['subscribe'] : 0;
            $user->nickname = $user_original['nickname'];
            $user->sex = $user_original['sex'];
            $user->language = $user_original['language'];
            $user->city = $user_original['city'];
            $user->province = $user_original['province'];
            $user->country = $user_original['country'];
            $user->headimgurl = $user_original['headimgurl'];
            $user->subscribe_time = isset($user_original['subscribe_time']) ? $user_original['subscribe_time'] : 0;
            $user->remark = isset($user_original['remark']) ? $user_original['remark'] : '';
            $user->groupid = isset($user_original['groupid']) ? $user_original['groupid'] : 0;
            $user->save();
            // 跳转到绑定用户界面
            return redirect()->guest(route('weChat.bindUser'));
        } elseif ($user->user_id == 0) {
            // 跳转到绑定用户界面
            return redirect()->guest(route('weChat.bindUser'));
        }
        return $next($request);
    }
}