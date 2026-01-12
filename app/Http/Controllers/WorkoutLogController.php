<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkoutLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutLogController extends Controller
{
    // 일지 작성 화면 (루틴 목록을 같이 보냅니다)
    public function create() {
        /** @var User $user */
        $user = Auth::user();
        $templates = $user->templates;
        
        return view('logs.create', compact('templates'));
    }

    // 일지 저장
    public function store(Request $request) {
        $request->validate([
            'record_date' => 'required|date',
            'workout_results' => 'required|array',
            'current_weight' => 'nullable|numeric',
        ]);

        /** @var User $user */
        $user = Auth::user();

        $user->logs()->create([
            'record_date' => $request->record_date,
            'workout_results' => $request->workout_results,
            'current_weight' => $request->current_weight,
            'diet_content' => $request->diet_content,
        ]);

        return redirect()->route('dashboard')->with('success', '오늘의 운동 기록 완료!');
    }
}