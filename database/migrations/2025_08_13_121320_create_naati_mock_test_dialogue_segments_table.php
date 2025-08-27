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
        Schema::create('naati_mock_test_dialogue_segments', function (Blueprint $table) {
            $table->id();
            $table->string('segment_path'); 
            $table->string('sample_response');
            $table->text('answer_eng')->nullable();
            $table->text('answer_other_language')->nullable();
            $table->bigInteger('dialogue_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('naati_mock_test_dialogue_segments');
    }
};
