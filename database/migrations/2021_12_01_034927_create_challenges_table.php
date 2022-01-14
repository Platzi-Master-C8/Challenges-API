<?php

use App\Constants\ChallengeDifficulties;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallengesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->text('description');
            $table->tinyInteger('time_out');
            $table->enum('difficulty', ChallengeDifficulties::toArray());

            $table->text('func_template');
            $table->text('test_template');
            //TODO: Answers
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('challenges');
    }
}
