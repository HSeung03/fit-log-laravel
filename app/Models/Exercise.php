<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    // DB 저장을 허용하는 필드들입니다.
    protected $fillable = ['user_id', 'name', 'category'];
}