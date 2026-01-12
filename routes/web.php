<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkoutTemplateController;
use App\Http\Controllers\WorkoutLogController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/', function () {
    // 1. 가짜 메시지 데이터 생성 (이게 없으면 image_b58f01.png 에러 발생)
    $chatMessages = [
        "안녕하세요! 무엇을 도와드릴까요?",
        "라라벨 게시판을 만들고 싶어요.",
        "좋습니다. 에러부터 하나씩 잡아볼까요?",
        "네, 로그아웃 에러가 나요!"
    ];

    // 2. 데이터를 'chatMessages'라는 이름으로 뷰에 전달
    return view('welcome', ['chatMessages' => $chatMessages]);
});

require __DIR__.'/auth.php';


Route::middleware(['auth'])->group(function () {
    Route::get('/templates', [WorkoutTemplateController::class, 'index'])->name('templates.index');
    Route::post('/templates', [WorkoutTemplateController::class, 'store'])->name('templates.store');
});

Route::middleware(['auth'])->group(function () {
    // ... 기존 템플릿 라우트들 ...
    Route::get('/logs/create', [WorkoutLogController::class, 'create'])->name('logs.create');
    Route::post('/logs', [WorkoutLogController::class, 'store'])->name('logs.store');
});