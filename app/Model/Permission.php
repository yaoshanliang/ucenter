<?php namespace App\Model;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission {

	//
    protected $fillable = ['app_id', 'group_id', 'group_order_id', 'order_id', 'name', 'title', 'description'];

    public function group() {
         return $this->hasOne('App\Model\Permission', 'id', 'group_id');
    }
    // public function belongsgroup() {
         // return $this->belongsTo('App\Model\Permission', 'group_id', 'id');
    // }


}
