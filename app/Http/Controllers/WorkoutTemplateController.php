<?php

namespace App\Http\Controllers;

use App\Models\WorkoutTemplate;
use App\Models\User; // 1. User 모델을 명시적으로 불러옵니다.
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutTemplateController extends Controller
{
    public function index() {
        /** @var User $user */ // 2. 에디터에게 $user가 User 모델임을 알려주는 힌트입니다.
        $user = Auth::user();
        
        $templates = $user->templates; 
        return view('templates.index', compact('templates'));
    }

    public function store(Request $request) {
        $request->validate([
            'template_name' => 'required|string|max:255',
            'exercises' => 'required|array',
        ]);

        /** @var User $user */ // 3. 여기서도 똑같이 힌트를 줍니다.
        $user = Auth::user();

        // 이제 $user->templates()에 빨간 줄이 생기지 않습니다.
        $user->templates()->create([
            'template_name' => $request->template_name,
            'routine_contents' => $request->exercises,
        ]);

        return redirect()->back()->with('success', '루틴이 저장되었습니다!');
    }
}