<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\Traits\LogsActivity;
use App\User;

class OrganicGroup extends Model
{

    use SoftDeletes;

    use LogsActivity;
    protected static $logAttributes = ['*'];
    protected static $logAttributesToIgnore = [ 'none'];
    protected static $logOnlyDirty = true;
    

    //1个用户属于多个小组
    //1个小组拥有多个成员/成员可以是用户或 messages/contents
    protected $fillable = [
        'memberable_type',  // User::class / Post::class / Message::class(暂时不重构了)
        'memberable_id',  // 1
        'groupable_type', // Live:class
        'groupable_id', // 1
        // 用户 申请 加入小组后，需要创建者审核 approve
        // 默认是1，即内容属于小组。 
        // 如果 memberable_type == User, 则需要默认是0，updated_at即为正式加入（审核通过）时间，加入时间
        'approved',
        'extra_attributes',
    ];


    /**
    * primaryKey 
    * 
    * @var integer
    * @access protected
    */
    protected $primaryKey = null;

    /**
    * Indicates if the IDs are auto-incrementing.
    *
    * @var bool
    */
    public $incrementing = false;


    //保存approved数字之前判断$memberNeedApprove 是否开启 审核加入小组
    public function setApprovedAttribute()
    {
        // 默认是1，即内容属于小组。 
        $this->attributes['approved'] = 1;
        if(
            $this->memberable_type == User::class && // 加入小组的对象 是 用户
            $this->groupable_type::$memberNeedApprove  // 且 需要创建者审核
        ){
            $this->attributes['approved'] = 0;
        }
    }


    // The morphTo method defines a polymorphic relationship. Personally I find the name to be a poor choice; when you read it just think "belongs To" but for polymorphic relationships, since the record will belong to whatever model is defined in the commentable_type field. This defines just one side of the relationship; you'll also want to define the inverse relation within any model that will be commentable, creating a method that determines which model is used to maintain the comments, and referencing the name of the method used in the polymorphic model:
    // The Comment model has a commentable() function that returns a morphTo() function indicating that this class is related to other models.
    // Now this means that now one or more models can use this Comment model. In our example, It can be associated with Post and Video. Both use this same Comment model.
    // TODO: how use morphTo for member()/memberable() and group()?
    public function group()
    {
        $groupClassName = $this->groupable_type;
        return $groupClassName::find($this->groupable_id);
    }

    public function member()
    {
        return $this->memberable_type::find($this->memberable_id);
    }

}
