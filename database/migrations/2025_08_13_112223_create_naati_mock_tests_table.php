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
        Schema::create('naati_mock_tests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->bigInteger('language_id');
            $table->integer('duration');
            $table->bigInteger('dialogue_one_id')->nullable();
            $table->bigInteger('dialogue_two_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('naati_mock_tests');
    }
};
