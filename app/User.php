<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Traits\HasSchemalessAttributes;

use App\Models\Message;
use App\Models\Social;
use App\Models\OrganicGroup;
use App\Traits\OrganicGroup\Memberable;

class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;
    use HasRoles;

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

    public function socials()
    {
        return $this->hasMany(Social::class);
    }

    // Memberable begin
    // use App\Models\OrganicGroup;
    // use App\Traits\OrganicGroup\Memberable;
    use Memberable; 
    // $user->is_memberable = ture;
    //$user->groups() // 获取我所属的小组！

    // 如果删除一个User，因为User是Memberable，需要删除OG表中所有与该用户有关的关items
    // 定义数据库约束不行，因为 表/memberable_type 不确定是User还是Post！
    protected static function boot() {
        parent::boot();
        static::deleting(function($model) {
            OrganicGroup::where('memberable_type', self::CLASS)
                ->where('memberable_id', $model->id)
                ->delete();
        });
    }
    // Memberable end
}
