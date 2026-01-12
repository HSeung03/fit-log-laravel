<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutTemplate extends Model
{
    //
    protected $fillable = ['user_id', 'template_name', 'routine_contents'];

    // JSON 데이터를 배열로 자동 변환해주는 설정
    protected $casts = [
        'routine_contents' => 'array',
    ];

    // 이 루틴의 주인(유저)
    public function user() {
        return $this->belongsTo(User::class);
    }
}
