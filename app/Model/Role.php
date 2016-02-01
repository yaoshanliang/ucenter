<?php
namespace App\Model;

use Zizaco\Entrust\EntrustRole;
use Zizaco\Entrust\Traits\EntrustRoleTrait;
use Session;

class Role extends EntrustRole
{
	use EntrustRoleTrait;

    protected $fillable = ['app_id', 'name', 'title', 'description'];

    public function scopeWhereDataTables($query, $post, $fields = array())
    {
		if (strlen($post['search']['value'])) {
            $query->where(function ($query) use ($post, $fields) {
                foreach ($fields as $k => $v) {
                    if ($k == 0) {
			            $query->where($v, 'LIKE',  '%' . $post['search']['value'] . '%');
                    } else {
                        $query->orWhere($v, 'LIKE',  '%' . $post['search']['value'] . '%');
                    }
                }
            });
        }
        return $query;
    }

    public function scopeOrderByDataTables($query, $post)
    {
        foreach ($post['order'] as $k => $v) {
            $query->orderBy($post['columns'][$v['column']]['data'], $v['dir']);
        }
        return $query;
    }

    public function perms()
    {
        $instance = $this->belongsToMany('App\Model\Permission', 'role_permission', 'role_id', 'permission_id');
        return $instance;
    }
}
