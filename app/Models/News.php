<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use SoftDeletes;
    protected $table    = 'news';
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

    /**
     * 新闻所在栏目
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Categories::class, 'cat_id');
    }

    /**
     * 评论
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comments::class, 'module');
    }
}
