<?php
/**
 * Created by PhpStorm.
 * User: wanggaichao @gai871013
 * Date: 2017-08-15
 * Time: UTC/GMT+08:00 10:34
 * laravel-manage/UserReponsitory.php
 */

namespace App\Repositories;


use App\User;

class UserRepository
{
    /**
     * 注入的User model
     * @var User
     */
    protected $user;

    /**
     * 构造函数
     * UserRepository constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

}