<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use SoftDeletes;
    protected $table = 'news';
    protected $fillable = [
        'title',
        'pinyin',
        'english',
        'cat_id',
        'description',
        'thumb',
        'style',
        'meta_keywords',
        'posids',
        'url',
        'sort',
        'status',
        'islink',
        'content',
        'paytype',
        'vote_id',
        'allow_comment',
        'copyfrom',
        'template',
        'read',
        'like'
    ];
}
