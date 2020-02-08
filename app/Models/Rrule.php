<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Traits\HasSchemalessAttributes;

class Rrule extends Model
{
    use SoftDeletes;

    use LogsActivity;
    protected static $logAttributes = ['*'];
    protected static $logAttributesToIgnore = [ 'none'];
    protected static $logOnlyDirty = true;

    use HasSchemalessAttributes;
    const EXTRA_ATTRIBUTES = ['to','do'];
    public $casts = [
    	'start_at' => 'datetime',
    	'end_at' => 'datetime',
        'extra_attributes' => 'array',
    ];

}
