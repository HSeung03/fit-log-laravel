<?php

namespace App\Http\Controllers;

use App\Models\WorkoutTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutTemplateController extends Controller
{
    // 루틴 목록 보기
    public function index() {
        $templates = Auth::user()->templates;
        return view('templates.index', compact('templates'));
    }

    // 루틴 저장하기
    public function store(Request $request) {
        $request->validate([
            'template_name' => 'required|string|max:255',
            'exercises' => 'required|array', // 운동 종목들이 배열로 들어옵니다.
        ]);

        Auth::user()->templates()->create([
            'template_name' => $request->template_name,
            'routine_contents' => $request->exercises, // 모델에서 array로 cast 설정했으므로 바로 저장 가능!
        ]);

        return redirect()->back()->with('success', '루틴이 저장되었습니다!');
    }
}