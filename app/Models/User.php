<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    // 权限 & 角色
    use HasRoles;

    // 消息通知
    use Notifiable {
        notify as protected laravelNotify;
    }

    // 发通知的方法
    public function notify ($instance)
    {
        // 如果要通知的人是当前用户，就不必通知了
        if ($this->id == \Auth::id()) {
            return ;
        }
        $this->increment('notification_count');
        $this->laravelNotify($instance);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'introduction'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // 用户与话题，一对多关联
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    // 用户 & 回复，一对多关联
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    // 策略授权
    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }

    // 清除未读消息标示
    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }
}
