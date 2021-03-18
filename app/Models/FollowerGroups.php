<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class FollowerGroups extends Model
{
    protected $fillable = ['id', 'name', 'count', 'created_at', 'updated_at'];
}
