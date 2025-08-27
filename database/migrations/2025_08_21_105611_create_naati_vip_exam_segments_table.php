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
        Schema::create('naati_vip_exam_segments', function (Blueprint $table) {
            $table->id();
            $table->string('segment_path');
            $table->text('answer_eng')->nullable();
            $table->text('answer_other_language')->nullable();
            $table->string('sample_response');
            $table->bigInteger('dialogue_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('naati_vip_exam_segments');
    }
};
