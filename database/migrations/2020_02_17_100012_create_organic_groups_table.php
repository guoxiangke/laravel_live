<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganicGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organic_groups', function (Blueprint $table) {
            $table->morphs('memberable');
            $table->morphs('groupable');
            // 用户 申请 加入小组后，需要创建者审核 approve
            // 默认是1，即内容属于小组。 
            // 如果 memberable_type == User, 则需要默认是0，updated_at即为正式加入（审核通过）时间，加入时间
            $table->boolean('approved')->default(1);
            $table->timestamps(); //加入小组时间，内容创建时间
            $table->softDeletes(); //离开小组时间，内容remove from小组时间（一般不用）
            // organic_groups_memberable_type_memberable_id_groupable_type_groupable_id_primary too long
            $table->primary(['memberable_type', 'memberable_id', 'groupable_type', 'groupable_id'],'organic_groups_memberable_groupable_primary');

            // Cacade delete through a polymorphic relationship
                // 定义数据库约束不行，因为 表/memberable_type 不确定是User还是Post！
                // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // @see App\User::boot()
            // @see App\Models\Live::boot()
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organic_groups');
    }
}
