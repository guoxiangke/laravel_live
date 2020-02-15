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
            $table->unsignedBigInteger('user_id'); // from_uid
            $table->text('message');
            // Model
                // Live: 直播聊天室
                // Chat: 一对一聊天
                // Group:多人聊天室
            //model_id
            $table->morphs('messageable');
            // messageable_id - integer
            // messageable_type - string
            $table->schemalessAttributes('extra_attributes');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
               ->references('id')->on('users')
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
