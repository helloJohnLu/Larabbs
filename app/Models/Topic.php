<?php

namespace App\Models;

class Topic extends Model
{
    // 可写入字段
    protected $fillable = ['title', 'body','category_id','excerpt', 'slug'];

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

    // 话题 & 回复，一对多关联
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    // 话题排序
    public function scopeWithOrder($query, $order)
    {
        // 不同的排序 ，使用不同的数据读取逻辑
        switch ($order) {
            case 'recent':
                $query = $this->recent();
                break;
            default:
                $query = $this->recentReplied();
                break;
        }

        // 预加载防止 N+1 问题
        return $query->with('user', 'category');
    }

    public function scopeRecentReplied($query)
    {
        // 当话题有新回复时，我们将编写逻辑来更新话题模型的 reply_count 属性，
        // 此时会自动触发框架对数据模型 updated_at 时间戳的更新
        return $query->orderBy('updated_at', 'desc');
    }

    public function scopeRecent($query)
    {
        // 按照创建时间排序
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Slug URL
     *
     * @param array $params
     * @return string
     */
    public function link($params = [])
    {
        return route('topics.show', array_merge([$this->id, $this->slug], $params));
    }
}
