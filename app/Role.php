<?php namespace App;

use Zizaco\Entrust\EntrustRole;

use Illuminate\Database\Eloquent\SoftDeletes;
class Role extends EntrustRole {

	use SoftDeletes;
	protected $dates = ['deleted_at'];

	public function users()
	{
		return $this->hasMany('App\UserRole');
	}

}
