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
        Schema::create('naati_plan_dialogue', function (Blueprint $table) {
            
            $table->id();
            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('dialogue_id');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('naati_plan_dialogue');
    }
};
