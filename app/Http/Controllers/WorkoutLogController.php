<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkoutLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutLogController extends Controller
{
    public function index() {
        if (!Auth::check()) {
            
            return view('welcome');
        }

        /** @var User $user */
        $user = Auth::user();

        // 운동 기록이 있는 날짜만 가져와서 배경 이벤트로 변환
        $logs = $user->logs()->get()->map(function($log) {
            return [
                'id' => $log->id,
                'start' => $log->record_date, 
                'display' => 'background',    
                'allDay' => true,
                'backgroundColor' => '#dbeafe', 
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

    /**
     * 운동 기록 저장 및 업데이트 로직
     */
    public function store(Request $request) {
    // 1. 유효성 검사 (필드명 수정)
    $request->validate([
        'record_date' => 'required|date',
        'exercise_ids' => 'required|array', // workout_results 대신 실제 넘어오는 input 이름
    ]);

    /** @var User $user */
    $user = Auth::user();

    // 2. [중요] 여러 개의 배열(id, set, rep)을 하나의 workout_results 배열로 합치기
    $workoutResults = [];
    foreach ($request->exercise_ids as $index => $id) {
        $workoutResults[] = [
            'exercise_id' => $id,
            'sets' => $request->sets[$index] ?? 0,
            'reps' => $request->reps[$index] ?? 0,
            'weight' => $request->weights[$index] ?? 0,
        ];
    }

    // 3. DB 저장 (이게 성공해야 달력에서 $user->logs()를 가져올 때 파란색이 나옴)
    $user->logs()->updateOrCreate(
        ['record_date' => $request->record_date],
        [
            'workout_results' => $workoutResults, // 가공된 배열 저장
            'diet_content' => $request->diet_content,
            // current_weight는 모달에 input이 없으면 null 처리됨
        ]
    );

    return redirect()->route('home')->with('success', '운동 기록이 완료되었습니다!');
}
}