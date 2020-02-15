<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Traits\HasSchemalessAttributes;

class Live extends Model
{
    use SoftDeletes;

    use LogsActivity;
    protected static $logAttributes = ['*'];
    protected static $logAttributesToIgnore = [ 'none'];
    protected static $logOnlyDirty = true;
    
    use HasSchemalessAttributes;
    const EXTRA_ATTRIBUTES = ['numbers', 'do'];
    public $casts = [
        'extra_attributes' => 'array',
    ];

    protected $fillable = [
        'title', 
        'description', 
        'rrule_id',
        'user_id',
        'extra_attributes',
    ];

	public function user(){
    	return $this->belongsTo(User::Class);
    }

    public function rrule()
    {
        return $this->hasOne(Rrule::class);
    }

    public function getIsLiveAttribute()
    {
        return 0; //1 is live. todo check with rrule!
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
}
