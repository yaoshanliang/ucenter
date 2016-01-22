<?php
namespace App\Model;

use App\Model\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class App extends Model
{
	protected $table = 'apps';

	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $fillable = ['name', 'title', 'description', 'home_url', 'login_url', 'secret', 'user_id'];

    public function client()
    {
         // return $this->hasOne('App\Model\OauthClients', 'id', 'group_id');
    }
}
