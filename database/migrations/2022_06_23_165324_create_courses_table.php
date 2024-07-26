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
            $table->id();
            $table->string('title')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('achievement')->nullable();
            $table->text('finishing_achievement')->nullable();
            $table->string('cover')->nullable();
            $table->text('video')->nullable();
            $table->decimal('old_price', 8, 2)->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['pending', 'inactive', 'active', 'cancel'])->default('pending');
            $table->string('time')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
