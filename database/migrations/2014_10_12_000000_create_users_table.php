<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 60);
            $table->string('nick_name', 30);
            $table->boolean('is_admin')->default(false);
            $table->string('email')->unique();
            $table->text('profile_image');
            $table->bigInteger('strikes')->default(0);
            $table->string('sub', 120);
            $table->timestamp('created_at');

            $table->foreignId('country_id')->nullable()->constrained('countries')->restrictOnDelete();
            $table->foreignId('gender_id')->nullable()->constrained('genders')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
