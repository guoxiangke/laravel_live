<?php

namespace App\Models;

use App\User;
use App\Models\OrganicGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Traits\HasSchemalessAttributes;
use App\Traits\OrganicGroup\Groupable;

class Live extends Model
{
    use SoftDeletes;

    use LogsActivity;
    protected static $logAttributes = ['*'];
    protected static $logAttributesToIgnore = [ 'none'];
    protected static $logOnlyDirty = true;
    
    use HasSchemalessAttributes;
    const EXTRA_ATTRIBUTES = ['before_mp4', 'before_img', 'playback_mp4'];
    public $casts = [
        'extra_attributes' => 'array',
        'start_at' => 'datetime',
    ];

    protected $fillable = [
        'title', 
        'description',
        'user_id',
        'extra_attributes',
        'start_at', //开始时间
        'during_minutes',//120
    ];

	public function user(){
    	return $this->belongsTo(User::Class);
    }

    public function getIsLiveAttribute()
    {
        return $this->start_at < now() && $this->start_at->diffInMinutes(now()) < $this->during_minutes; //1 is live. todo check with rrule!
    }

    /**
     * 取得
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function messages()
    {
        return $this->morphMany(Message::class, 'messageable');
    }


    // Groupable begin
    // use App\Traits\OrganicGroup\Groupable;
    // use App\Models\OrganicGroup;
    use Groupable;
    // $live->is_groupable = ture;
    // $live->members() // 获取我所属的小组！public static $memberNeedApprove = true;

    // 如果删除一个Live，因为Live是Groupable，需要删除所有成员or属于此group的内容(OG表中的关联项目)
    // 以下方法：定义数据库约束不行，因为 表/groupable_type 不确定！
    // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    // Cacade delete through a polymorphic relationship
    // https://stackoverflow.com/questions/14174070/automatically-deleting-related-rows-in-laravel-eloquent-orm
    protected static function boot() {
        parent::boot();
        static::deleting(function($model) {
            $model->messages()->forceDelete(); // @se Live::messages()
            OrganicGroup::where('groupable_type', self::CLASS)
                ->where('groupable_id', $model->id)
                ->delete();
        });
    }
    // Groupable end
}
