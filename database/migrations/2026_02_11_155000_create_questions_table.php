<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->string('type')->default('multiple_choice'); // multiple_choice, text_input
            $table->string('difficulty')->default('easy'); // easy, medium, hard
            $table->string('section')->nullable(); // Algoritmické myšlení, Smyčky, etc.
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
};
