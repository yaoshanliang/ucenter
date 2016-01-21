<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class App extends Model {
	protected $table = 'apps';

	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $fillable = ['name', 'title', 'description', 'home_url', 'login_url', 'secret', 'user_id'];

    public function scopeWhereSearch($query, $request, $fields = array())
    {
		if (strlen($request->search['value'])) {
            $query->where(function ($query) use ($request, $fields) {
                foreach ($fields as $k => $v) {
                    if ($k == 0) {
			            $query->where($v, 'LIKE',  '%' . $request->search['value'] . '%');
                    } else {
                        $query->orWhere($v, 'LIKE',  '%' . $request->search['value'] . '%');
                    }
                }
            });
        }
        return $query;
    }

    public function scopeOrderByArray($query, $request)
    {
        foreach ($request->order as $k => $v) {
            $query->orderBy($request->columns[$v['column']]['data'], $v['dir']);
        }
        return $query;
    }
}
