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
        Schema::create('naati_user_mock_test_dialogue_segments', function (Blueprint $table) {
            $table->id();
            $table->string('segment_path');
            $table->bigInteger('user_dialogue_id');
            $table->bigInteger('segment_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('naati_user_mock_test_dialogue_segments');
    }
};
