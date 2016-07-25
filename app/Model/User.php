<?php
namespace App\Model;

use App\Model\BaseModel as Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

use Zizaco\Entrust\Traits\EntrustUserTrait;
use Cache;
use Config;
use Session;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{

    use Authenticatable, CanResetPassword;
    use EntrustUserTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'email', 'phone', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function appRoles()
    {
        $instance = $this->belongsToMany('App\Model\Role', 'user_role', 'user_id', 'role_id');
        $instance->wherePivot('app_id', Session::get('current_app_id'));
        return $instance;
    }

    // important! 当前应用、当前角色，hasRole/can/ability调用
    public function roles()
    {
        $instance = $this->belongsToMany('App\Model\Role', 'user_role', 'user_id', 'role_id');
        $instance->wherePivot('app_id', Session::get('current_app_id'));
        $instance->wherePivot('role_id', Session::get('current_role_id'));
        return $instance;
    }

}
