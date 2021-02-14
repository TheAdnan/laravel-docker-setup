<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->bigInteger('user_id')->unsigned()->index();
            $table->bigInteger('url_id')->unsigned()->index();
            $table->boolean('suggested')->nullable()->default(true);
            $table->unsignedInteger('repetitions')->nullable()->default(0);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('url_id')->references('id')->on('urls');

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
        Schema::dropIfExists('tags');
    }
}
