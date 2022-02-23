<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (env('APP_ENV') == 'testing' || env('APP_ENV') == 'local') {

            Schema::create('genders', function (Blueprint $table) {
                $table->id();
                $table->string('name', 50);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (env('APP_ENV') == 'testing' || env('APP_ENV') == 'local') {

            Schema::dropIfExists('genders');
        }
    }
}
