<?php

namespace App\Models;

class Topic extends Model
{
    // 可写入字段
    protected $fillable = ['title', 'body', 'user_id', 'category_id', 'reply_count', 'view_count', 'last_reply_user_id', 'order', 'excerpt', 'slug'];

    // 话题与分类，一对一关联
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // 话题与用户，一对一关联
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
