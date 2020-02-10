<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Traits\HasSchemalessAttributes;

class Social extends Model
{
	use LogsActivity;
    protected static $logAttributes = ['*'];
    protected static $logAttributesToIgnore = [ 'none'];
    protected static $logOnlyDirty = true;

    use HasSchemalessAttributes;
    const EXTRA_ATTRIBUTES = ['to','do'];
    public $casts = [
        'extra_attributes' => 'array',
    ];

    const TYPE_WECHAT = 0;
    const TYPE_FACEBOOK = 1;
    const TYPE_GITHUB = 2;
    // A person is assigned a unique page-scoped ID (PSID) for each Facebook Page they start a conversation with. The PSID is used by your Messenger bot to identify a person when sending messages.
    const TYPE_FB_PSID = 3;

    const TYPES = ['微信', 'Github', 'Facebook', 'PSID'];
    protected $fillable = [
        'social_id', //（openid）
        'type', //0:wechat 1:github 2:facebook 3:psid
        'user_id',
        'name',
        'avatar',
    ];


    public function user(){
    	return $this->belongsTo(User::Class);
    }
}
