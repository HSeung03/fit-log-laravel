<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkoutLog;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutLogController extends Controller
{
    /**
     * 메인 페이지 (달력)
     */
    public function index() {
        if (!Auth::check()) {
            return view('welcome');
        }

        /** @var User $user */
        $user = Auth::user();

        // 인텔리센스 에러 방지를 위해 $user->logs 직접 호출 대신 관계 사용
        $logs = $user->logs()->get()->map(function($log) {
            return [
                'id' => $log->id,
                'start' => $log->record_date->format('Y-m-d'), 
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
     * 운동 기록 저장 및 업데이트
     */
    public function store(Request $request) {
        $request->validate([
            'record_date' => 'required|date',
            'exercise_ids' => 'required|array',
        ]);

        /** @var User $user */
        $user = Auth::user();

        $workoutResults = [];
        foreach ($request->exercise_ids as $index => $id) {
            $workoutResults[] = [
                'exercise_id' => $id,
                'sets' => $request->sets[$index] ?? 0,
                'reps' => $request->reps[$index] ?? 0,
                'weight' => $request->weights[$index] ?? 0,
            ];
        }

        $user->logs()->updateOrCreate(
            ['record_date' => $request->record_date],
            [
                'workout_results' => $workoutResults,
                'diet_content' => $request->diet_content,
            ]
        );

        return redirect()->route('home')->with('success', '운동 기록이 완료되었습니다!');
    }

    /**
     * 1️⃣ 운동기록 목록 페이지 (요약): /logs
     */
    public function list() {
        /** @var User $user */
        $user = Auth::user();

        $logs = $user->logs()
            ->orderBy('record_date', 'desc')
            ->get(['record_date']); 

        return view('logs.index', compact('logs'));
    }

    /**
     * 2️⃣ 날짜별 상세 기록 페이지: /logs/{date}
     */
    public function show($date) {
        /** @var User $user */
        $user = Auth::user();

        $log = $user->logs()
            ->where('record_date', $date)
            ->firstOrFail();

        // [추가] JSON 안의 exercise_id를 실제 운동 이름으로 변환하기 위한 매핑
        // exercise_id들을 추출해서 한 번에 조회 (성능 최적화)
        $exerciseIds = collect($log->workout_results)->pluck('exercise_id');
        $exerciseNames = Exercise::whereIn('id', $exerciseIds)->pluck('name', 'id');

        return view('logs.show', compact('log', 'exerciseNames'));
    }
}