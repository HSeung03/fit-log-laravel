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
        $exercises = $user->exercises()->orderBy('category')->get();
        return view('workouts.exercises', compact('exercises'));
    }

    // 새 운동 저장하기
    public function store(Request $request)
{
    // ... 유효성 검사 로직 ...

    /** @var \App\Models\User $user */
    $user = Auth::user();
    $user->exercises()->create($request->all());

    return redirect()->back()->with('success', '새로운 운동이 등록되었습니다!');
}
}