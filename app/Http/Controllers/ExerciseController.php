<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExerciseController extends Controller
{
    // 내 운동 목록 보기
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // $user가 null일 경우를 대비해 안전하게 처리
        $exercises = $user ? $user->exercises()->orderBy('category')->get() : collect();
        
        return view('workouts.exercises', compact('exercises'));
    }

    // 새 운동 저장하기
    public function store(Request $request)
    {
        // 유효성 검사 추가 (최소한의 보안)
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->exercises()->create($request->all());

        return redirect()->back()->with('success', '새로운 운동이 등록되었습니다!');
    }

    // 운동 삭제하기 (추가된 부분)
    public function destroy(Exercise $exercise)
    {
        // 보안: 현재 로그인한 사용자의 운동인지 확인
        // 빨간 줄 방지를 위해 Auth::id() 사용
        if ($exercise->user_id !== Auth::id()) {
            abort(403, '권한이 없습니다.');
        }

        $exercise->delete();

        return redirect()->route('exercises.index')->with('success', '운동이 삭제되었습니다.');
    }
}