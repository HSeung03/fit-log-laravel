<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutLog extends Model
{
    //
    protected $fillable = ['user_id', 'record_date', 'diet_content', 'workout_results', 'current_weight'];

    protected $casts = [
        'workout_results' => 'array',
        'record_date' => 'date',
    ];

    // 이 일지의 작성자(유저)
    public function user() {
        return $this->belongsTo(User::class);
    }
}
