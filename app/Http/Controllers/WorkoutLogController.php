<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkoutLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutLogController extends Controller
{
    // 메인 페이지(달력) 데이터 로드
    // WorkoutLogController.php

public function index() {
    if (!Auth::check()) {
        return view('welcome', ['chatMessages' => ["반갑습니다!"]]);
    }

    /** @var User $user */
    $user = Auth::user();

    $logs = $user->logs()->get()->map(function($log) {
        return [
            'id' => $log->id,
            'title' => "완료",
            'start' => $log->record_date,
            'allDay' => true,
            // ★ 하늘색 계열 설정 (Tailwind sky-400 느낌)
            'backgroundColor' => '#c8eaf8', 
            'borderColor' => '#74e6e6',
            'textColor' => '#030303',
            'extendedProps' => [
                'weight' => $log->current_weight,
                'diet' => $log->diet_content,
                'results' => $log->workout_results
            ]
        ];
    });

    $exercisesByCategory = $user->exercises->groupBy('category');
    return view('welcome', compact('logs', 'exercisesByCategory'));
}

public function store(Request $request) {
    $request->validate([
        'record_date' => 'required|date',
        'workout_results' => 'required|array',
        'current_weight' => 'nullable|numeric',
    ]);

    /** @var User $user */
    $user = Auth::user();

    // ★ 같은 날짜에 저장할 경우 새로운 글을 만들지 않고 기존 글을 수정(업데이트)함
    $user->logs()->updateOrCreate(
        ['record_date' => $request->record_date], // 조건
        [
            'workout_results' => $request->workout_results,
            'current_weight' => $request->current_weight,
            'diet_content' => $request->diet_content,
        ]
    );

    return redirect()->route('home')->with('success', '운동 기록이 완료되었습니다!');
}
}