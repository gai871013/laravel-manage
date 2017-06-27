<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminAction extends Model
{
    use SoftDeletes;
    protected $table = 'admin_actions';
}
