<?php
namespace App\Model;

use App\Model\Model;
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
    protected $fillable = ['username', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    // 获取用户信息
    protected function getUserInfo($user_id) {
        $user_info = Cache::get(Config::get('cache.users') . $user_id, function() use ($user_id) {
            $user_info = User::findOrFail($user_id)->toArray();
            Cache::forever(Config::get('cache.users') . $user_id, $user_info);
            return $user_info;
        });
        return $user_info;
    }

    // public function scopeRoles($query, $user_id)
    // {
        // return $this->belongsToMany('App\Model\Role', 'user_role', 'user_id', 'role_id');
    // }

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
