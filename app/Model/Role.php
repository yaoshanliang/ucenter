<?php namespace App\Model;

use Zizaco\Entrust\EntrustRole;

use Zizaco\Entrust\Traits\EntrustRoleTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
class Role extends EntrustRole {

	use EntrustRoleTrait;
	use SoftDeletes;
	protected $dates = ['deleted_at'];

}
