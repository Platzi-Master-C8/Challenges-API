<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (env('APP_ENV') == 'testing' || env('APP_ENV') == 'local') {

            Schema::create('countries', function (Blueprint $table) {
                $table->id();
                $table->string('name', 50);
                $table->string('iso_alpha_2', 2);
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
            Schema::dropIfExists('countries');
        }
    }
}
