<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkoutTemplateController;
use App\Http\Controllers\WorkoutLogController;
use App\Http\Controllers\ExerciseController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// 1. 메인 페이지 (달력 화면)
Route::get('/', [WorkoutLogController::class, 'index'])->name('home');

// 2. 인증된 사용자 전용 그룹
Route::middleware(['auth', 'verified'])->group(function () {
    
    // 대시보드 접속 시 메인으로 리다이렉트
    Route::get('/dashboard', function () {
        return redirect()->route('home');
    })->name('dashboard');

    // --- 운동 일지 관련 ---
    // 기록 저장
    Route::post('/logs', [WorkoutLogController::class, 'store'])->name('logs.store');
    
    // [추가] 기록 목록 보기 (요약 페이지)
    Route::get('/logs', [WorkoutLogController::class, 'list'])->name('logs.index');
    
    // [추가] 날짜별 상세 기록 보기 (읽기 전용)
    Route::get('/logs/{date}', [WorkoutLogController::class, 'show'])->name('logs.show');

    // --- 기타 관리 기능 ---
    // 프로필 관리
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 운동 종류 관리
    Route::resource('exercises', ExerciseController::class)->only(['index', 'store', 'destroy']);

    // 템플릿 관리
    Route::resource('templates', WorkoutTemplateController::class)->only(['index', 'store']);
});

require __DIR__.'/auth.php';