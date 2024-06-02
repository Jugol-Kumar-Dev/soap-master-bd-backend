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
        Schema::create('mocktest_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mocktest_id');
            $table->unsignedBigInteger('user_id');
            $table->string('exam_token')->nullable();
//            $table->decimal('mark', 8,2)->default(0.00);
//            $table->float('get_parsentage')->nullable();
//            $table->integer('total_correct')->nullable();
//            $table->integer('total_incaorrect')->nullable();
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
        Schema::dropIfExists('mocktest_user');
    }
};
