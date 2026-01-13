<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkoutTemplateController;
use App\Http\Controllers\WorkoutLogController;
use App\Http\Controllers\ExerciseController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/test-url', function() {
    return "연결 성공!";
});
// 메인 페이지
Route::get('/', function () {
    $chatMessages = [
        "안녕하세요! 무엇을 도와드릴까요?",
        "라라벨 게시판을 만들고 싶어요.",
        "좋습니다. 에러부터 하나씩 잡아볼까요?",
        "네, 로그아웃 에러가 나요!"
    ];
    return view('welcome', ['chatMessages' => $chatMessages]);
});

// 로그인한 사용자 전용 그룹 (하나로 합치기)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // 대시보드
    Route::get('/dashboard', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return view('dashboard', ['templates' => $user->templates]);
    })->name('dashboard');

    // 프로필 관리
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 루틴 템플릿 관리
    Route::get('/templates', [WorkoutTemplateController::class, 'index'])->name('templates.index');
    Route::post('/templates', [WorkoutTemplateController::class, 'store'])->name('templates.store');

    // 운동 일지(로그) 관리
    Route::get('/logs/create', [WorkoutLogController::class, 'create'])->name('logs.create');
    Route::post('/logs', [WorkoutLogController::class, 'store'])->name('logs.store');

    // 운동 종류(Exercise) 관리 - 404가 난다면 여기가 핵심!
    Route::get('/exercises', [ExerciseController::class, 'index'])->name('exercises.index');
    Route::post('/exercises', [ExerciseController::class, 'store'])->name('exercises.store');
});

require __DIR__.'/auth.php';