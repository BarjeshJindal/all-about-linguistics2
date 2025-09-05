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
        Schema::create('naati_subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_type');
           // Access limits
            $table->integer('practice_dialogues_limit')->default(0);
            $table->integer('mock_tests_limit')->default(0);
            $table->integer('vip_exams_limit')->default(0);
           $table->integer('duration_days')->nullable(); // null means no expiry
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('naati_subscription_plans');
    }
};
