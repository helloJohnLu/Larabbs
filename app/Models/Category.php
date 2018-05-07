<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // 可写入字段
    protected $fillable = ['name', 'description'];


}
