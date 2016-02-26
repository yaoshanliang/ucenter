<?php
namespace App\Model;

use App\Model\Model;

class App extends Model
{
	protected $table = 'apps';

	protected $fillable = ['name', 'title', 'description', 'home_url', 'login_url', 'user_id'];

    public function client()
    {
         // return $this->hasOne('App\Model\OauthClients', 'id', 'group_id');
    }
}
