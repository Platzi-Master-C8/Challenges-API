<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBadgeChallengerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('badge_challenger', function (Blueprint $table) {
            $table->id();

            $table->foreignId('badge_id')->constrained('badges')->cascadeOnDelete();
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
        Schema::dropIfExists('badge_challenger');
    }
}
