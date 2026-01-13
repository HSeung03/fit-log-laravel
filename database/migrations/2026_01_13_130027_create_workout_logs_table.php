<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('workout_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 누구의 기록인지
        $table->date('record_date'); // 운동한 날짜
        $table->decimal('current_weight', 5, 2)->nullable(); // 오늘 체중
        $table->json('workout_results')->nullable(); // 운동 결과 (이름, 무게, 횟수)를 통째로 저장
        $table->text('diet_content')->nullable(); // 식단 및 메모
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_logs');
    }
};
