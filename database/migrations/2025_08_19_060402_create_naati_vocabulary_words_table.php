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
        Schema::create('naati_vocabulary_words', function (Blueprint $table) {
            $table->id();
            $table->string('word');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('language_id');
            $table->longText('meaning');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('naati_vocabulary_words');
    }
};
