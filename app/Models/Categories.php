<?php
/**
 * Created by PhpStorm.
 * User: wanggaichao @gai871013
 * Date: 2017-07-25
 * Time: UTC/GMT+08:00 16:16
 * laravel-manage/Categories.php
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table = 'categories';
    protected $fillable = [
        'module',
        'type',
        'parent_id',
        'arr_parent_id',
        'child',
        'child_id',
        'catname',
        'thumb',
        'style',
        'description',
        'url',
        'hits',
        'setting',
        'sort',
        'is_menu',
        'letter',
    ];
}