<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkoutLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutLogController extends Controller
{
    // 메인 페이지(달력) 데이터 로드
    public function index() {
    if (!Auth::check()) {
        return view('welcome', ['chatMessages' => ["반갑습니다!"]]);
    }

    /** @var User $user */
    $user = Auth::user();

    // 1. 달력 이벤트 데이터 (기록이 있는 날 표시용)
    $logs = $user->logs()->get()->map(function($log) {
        return [
            'id' => $log->id,
            'title' => "💪 완료 ({$log->current_weight}kg)",
            'start' => $log->record_date,
            'backgroundColor' => '#22c55e', // 초록색 (Tailwind green-500)
            'borderColor' => '#16a34a',     // 진한 초록색 (Tailwind green-600)
            'display' => 'block',           // 이벤트를 바(bar) 형태로 꽉 채워 표시
            'extendedProps' => [
                'weight' => $log->current_weight,
                'diet' => $log->diet_content,
                'results' => $log->workout_results
            ]
        ];
    });

    // 2. 카테고리별 운동 목록
    $exercisesByCategory = $user->exercises->groupBy('category');

    return view('welcome', compact('logs', 'exercisesByCategory'));
}

    // 일지 저장 (기존 코드 유지 및 리다이렉트 수정)
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

        // 저장 후 메인 페이지로 이동
        return redirect()->route('home')->with('success', '오늘의 운동 기록 완료!');
    }
}