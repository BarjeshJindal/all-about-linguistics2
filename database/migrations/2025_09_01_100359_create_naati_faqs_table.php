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
        Schema::create('naati_faqs', function (Blueprint $table) {
            $table->id();
            $table->longText('question');  // Question can be a long paragraph
            $table->longText('answer');    // Answer can also be a long paragraph
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('naati_faqs');
    }
};
