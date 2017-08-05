<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    //
    protected $table = 'followers';
    protected $fillable = [
        'subscribe',
        'subscribe_time',
        'openid',
        'nickname',
        'sex',
        'province',
        'city',
        'country',
        'language',
        'headimgurl',
        'subscribe',
        'subscribe',
        'subscribe',
        'subscribe',
        'subscribe',
        'subscribe',
        'subscribe',
    ];

    /**
     * 获取绑定用户信息
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }


}
