<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Model extends Model
{
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
