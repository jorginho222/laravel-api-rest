<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 60)->comment("Course name");
            $table->string('description', 255)->comment("Course description");
            $table->integer('max_students')->comment("Maximum students allowed");
            $table->integer('available_places')->default(0)->comment('Available places for enrollment');
            $table->boolean('is_full')->default(false)->comment('There are not available places');
            $table->float('price',7,2)->comment("Course price");
            $table->float('rating')->default(0)->comment("Course average rating");
            $table->uuid('area_id');
            $table->uuid('user_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('area_id')->references('id')->on('areas');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
};
