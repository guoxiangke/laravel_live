<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('social_id')->index();//->comment('openid');
            $table->unsignedBigInteger('user_id');
            $table->string('name')->nullable();
            $table->string('avatar')->nullable();
            $table->unsignedTinyInteger('type')->default(0); //0:wechat 1:github 2:facebook 3:psid
            $table->schemalessAttributes('extra_attributes');
            $table->timestamps();

            $table->foreign('user_id')
               ->references('id')
               ->on('users')
               ->onDelete('cascade');
            // add 唯一索引在 social_id + type //确保一个用户在一个平台唯一绑定
            $table->unique(['social_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('socials');
    }
}
