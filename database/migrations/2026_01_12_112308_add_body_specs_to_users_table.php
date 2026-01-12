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
        Schema::table('users', function (Blueprint $table) {
        $table->float('height')->nullable()->after('password');         // 키
        $table->float('initial_weight')->nullable()->after('height');   // 초기 체중
        $table->float('initial_muscle')->nullable()->after('initial_weight'); // 초기 근육량
        $table->float('initial_fat')->nullable()->after('initial_muscle');    // 초기 체지방
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
