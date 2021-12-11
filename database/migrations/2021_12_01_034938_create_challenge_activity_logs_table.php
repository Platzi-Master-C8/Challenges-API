<?php

use App\Constants\ChallengeStatuses;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallengeActivityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenge_activity_logs', function (Blueprint $table) {
            $table->enum('status', ChallengeStatuses::toArray())->default(ChallengeStatuses::IN_PROGRESS);
            $table->timestamps();

            $table->foreignId('challenge_id')->constrained('challenges')->cascadeOnDelete();
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
        Schema::dropIfExists('challenge_activity_logs');
    }
}
