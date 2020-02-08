<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->text('message');
            $table->unsignedBigInteger('live_id');
            $table->schemalessAttributes('extra_attributes');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
               ->references('id')->on('users')
               ->onDelete('cascade');

            $table->foreign('live_id')
               ->references('id')->on('lives')
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
        Schema::dropIfExists('messages');
    }
}
