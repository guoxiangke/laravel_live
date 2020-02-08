<?php

namespace App\Models;

use App\Traits\HasSchemalessAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Profile extends Model implements HasMedia
{
    use SoftDeletes;
    const SEXS = [
        '女', //0
        '男', //1
    ];
    
    use LogsActivity;
    protected static $logAttributes = ['*'];
    protected static $logAttributesToIgnore = [ 'none'];
    protected static $logOnlyDirty = true;

    use HasMediaTrait;//附件
    use HasSchemalessAttributes;
    // todo 应用以下属性
    const EXTRA_ATTRIBUTES = ['t1','t2'];
    public $casts = [
        'extra_attributes' => 'array',
    ];


}
