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
        'user_id', 
        'live_id',
        'extra_attributes',
    ];

    public function user(){
    	return $this->belongsTo(User::Class);
    }
}
