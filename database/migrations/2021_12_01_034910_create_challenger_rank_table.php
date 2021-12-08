<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallengerRankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenger_rank', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_current')->default(false);
            $table->boolean('is_next')->default(false);
            $table->timestamps();

            $table->foreignId('rank_id')->constrained('ranks')->cascadeOnDelete();
            $table->foreignId('challenger_id')->constrained('challengers')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('challenger_rank');
    }
}
