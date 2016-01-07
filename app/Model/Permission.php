<?php namespace App\Model;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission {

	//
    protected $fillable = ['app_id', 'name', 'title', 'description'];

}
