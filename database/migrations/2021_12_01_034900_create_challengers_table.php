<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallengersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challengers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('points')->default(0);

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('rank_id')->constrained('ranks')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('challengers');
    }
}
