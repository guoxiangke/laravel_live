<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Traits\HasSchemalessAttributes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

use App\Models\Message;

class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;
    use HasRoles;
    use HasMediaTrait;//附件

    use LogsActivity;
    protected static $logAttributes = ['*'];
    protected static $logAttributesToIgnore = [ 'none'];
    protected static $logOnlyDirty = true;

    use HasSchemalessAttributes;
    const EXTRA_ATTRIBUTES = ['to','do'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
        'avatar',
        'extra_attributes',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'extra_attributes' => 'array',
    ];

    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }


}
