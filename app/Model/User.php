<?php namespace App\Model;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

use Zizaco\Entrust\Traits\EntrustUserTrait;
use Cache;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

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
        $user_info = Cache::get(env('CACHE_USERS_PREFIX') . $user_id, function() use ($user_id) {
            $user_info = User::findOrFail($user_id)->toArray();
            Cache::forever(env('CACHE_USERS_PREFIX') . $user_id, $user_info);
            return $user_info;
        });
        return $user_info;

    }
}
