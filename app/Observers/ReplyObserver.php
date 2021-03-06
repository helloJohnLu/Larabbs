<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    // 评论数自增 1
    public function created(Reply $reply)
    {
        $topic = $reply->topic;
        $reply->topic->increment('reply_count', 1);

        // 通知作者话题被回复了
        $topic->user->notify(new TopicReplied($reply));
    }

    // 防止 XSS 攻击
    public function creating(Reply $reply)
    {
        $reply->content = clean($reply->content, 'user_topic_body');
    }

    // 监控『删除回复』，回复数减1
    public function deleted(Reply $reply)
    {
        $reply->topic()->decrement('reply_count', 1);
    }
}