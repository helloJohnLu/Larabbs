<?php

namespace App\Models;

class Reply extends Model
{
    // 可写入字段
    protected $fillable = ['content'];

    // 回复 & 话题，一对一关联
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    // 回复 & 用户，一对一关联
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
