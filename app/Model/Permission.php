<?php
namespace App\Model;

use App\Model\Model;
use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    protected $fillable = ['app_id', 'group_id', 'group_order_id', 'order_id', 'name', 'title', 'description'];

    public function group()
    {
         return $this->hasOne('App\Model\Permission', 'id', 'group_id');
    }

    public function role()
    {
        return $this->belongsTo('App\Model\Permission', 'group_id', 'id');
    }

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

}
