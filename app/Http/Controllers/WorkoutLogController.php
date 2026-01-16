<?php
//이 파일이 위치한 경로를 알려줌
namespace App\Http\Controllers; 

use App\Models\User;
use App\Models\WorkoutLog;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutLogController extends Controller
{
    //로그인 안 한 사람은 welcome 페이지(소개화면)으로 보냄
    public function index() {
        if (!Auth::check()) {
            return view('welcome');
        }
        //$user변수안에 User변수가 있다는 것을 명시하기 위해서 사용
        /** @var User $user */ 
        //로그인한 사용자의 정보를 $user 변수에 담음
        //Auth의 인증의 줄임말로 현재 인증된 user의 모든 정보를 $user변수에 담음
        //::이거는 ->과 다르게 객체를 생성하지 않고 바로 실행하겠다는 뜻
        $user = Auth::user();

        // DB 모델 -> FullCalender가 이해할 수 있는 배열로 변환하는 작업
        //$user->logs() logs(운동기록)에 연결
        //-get():연결된 운동기록들을 데이터베이스에서 가져옴
        //->map(...) 가져온 데이터를 다시 가공
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
        //모달에서 카테고리 버튼 눌렀을 때 사용
        $exercisesByCategory = $user->exercises->groupBy('category');
        //뷰는 로직 없이 화면만 담당
        return view('welcome', compact('logs', 'exercisesByCategory'));
    }
    //운동 기록 및 저장
    public function store(Request $request) {
        //유효성 검사 
        $request->validate([
            'record_date' => 'required|date',
            'exercise_ids' => 'required|array',
        ]);

        /** @var User $user */
        $user = Auth::user();
        //배열 선언
        $workoutResults = [];
        //입력 데이터 구조 변환
        foreach ($request->exercise_ids as $index => $id) {
            $workoutResults[] = [
                'exercise_id' => $id,
                'sets' => $request->sets[$index] ?? 0,
                'reps' => $request->reps[$index] ?? 0,
                'weight' => $request->weights[$index] ?? 0,
            ];
        }
        //날짜 기준 업데이트 또는 생성
        $user->logs()->updateOrCreate(
            ['record_date' => $request->record_date],
            [
                'workout_results' => $workoutResults,
                'diet_content' => $request->diet_content,
            ]
        );

        return redirect()->route('home')->with('success', '운동 기록이 완료되었습니다!');
    }

    
    //운동기록 목록 페이지 (요약): /logs
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