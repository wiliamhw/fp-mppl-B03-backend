<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebinarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webinars', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->string('title', 255);
            $table->string('brief_description', 383);
            $table->mediumText('description');
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->integer('price')->unsigned();
            $table->string('type', 32);
            $table->string('zoom_id', 11)->nullable();
            $table->integer('participants')->unsigned()->default(0);
            $table->integer('max_participants')->unsigned()->default(0);
            $table->dateTime('published_at')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webinars');
    }
}
