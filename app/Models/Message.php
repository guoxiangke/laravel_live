<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\Traits\LogsActivity;
use App\Traits\HasSchemalessAttributes;
use App\User;

class Message extends Model
{
    use SoftDeletes;

    // use LogsActivity;
    // protected static $logAttributes = ['*'];
    // protected static $logAttributesToIgnore = [ 'none'];
    // protected static $logOnlyDirty = true;
    
    use HasSchemalessAttributes;
    const EXTRA_ATTRIBUTES = ['to','do'];
    public $casts = [
        'extra_attributes' => 'array',
    ];

    protected $fillable = [
        'message', 
        'user_id', //from_uid
        'messageable_id',
        'messageable_type',
        'extra_attributes',
    ];

    public function user(){
    	return $this->belongsTo(User::Class);
    }

    public function from(){
        return $this->user();
    }

    /**
     * to() 发送到哪里？发送给谁？ 
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function messageable()
    {
        return $this->morphTo();
    }
}
