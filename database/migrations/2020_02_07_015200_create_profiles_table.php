<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('first_name')->nullable()->comment('名');
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable()->comment('姓');
            $table->boolean('sex')->default(0); // 0
            $table->date('birthday')->nullable();
            //country. see telephone with(+)86
            $table->string('telephone', 22)->unique()->index(); //用来登陆账户9-13 with(+)86
            $table->unsignedBigInteger('recommend_uid')->nullable()->comment('用户关系');
            $table->schemalessAttributes('extra_attributes')->comment('扩展属性');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
               ->references('id')->on('users')
               ->onDelete('cascade');

            $table->foreign('recommend_uid')
               ->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
