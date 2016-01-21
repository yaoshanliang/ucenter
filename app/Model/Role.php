<?php namespace App\Model;

use Zizaco\Entrust\EntrustRole;

use Zizaco\Entrust\Traits\EntrustRoleTrait;
class Role extends EntrustRole {

	use EntrustRoleTrait;
    protected $fillable = ['app_id', 'name', 'title', 'description'];

}
