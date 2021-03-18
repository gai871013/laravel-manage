<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;
    protected $table = 'expenses';

    /**
     * 用户信息 2017-5-16 11:53:36 by gai871013
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 车辆信息 2017-5-16 11:53:29 by gai871013
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    /**
     * 行程 2017-5-16 11:53:23 by gai871013
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function journey()
    {
        return $this->belongsTo(Journey::class);
    }
}
