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
        // 1. 데이터 유효성 검사
        $request->validate([
            'record_date' => 'required|date',
            'workout_results' => 'required|array',
            'current_weight' => 'nullable|numeric',
        ]);

        /** @var User $user */
        $user = Auth::user();

        // 2. 해당 날짜에 기록이 있으면 업데이트, 없으면 새로 생성
        $user->logs()->updateOrCreate(
            ['record_date' => $request->record_date], // 조건: 같은 날짜
            [
                'workout_results' => $request->workout_results,
                'current_weight' => $request->current_weight,
                'diet_content' => $request->diet_content,
            ]
        );

        // 3. 저장 후 메인 페이지로 리다이렉트
        return redirect()->route('home')->with('success', '운동 기록이 완료되었습니다!');
    }
}