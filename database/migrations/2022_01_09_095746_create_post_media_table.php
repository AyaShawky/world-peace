<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->enum('type',['video','image']);
            $table->string('media');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('post_id')->references('id')->on('posts')->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_media');
    }
}
