<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class App extends Model {
	protected $table = 'apps';

	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $fillable = ['name', 'title', 'description', 'home_url', 'login_url', 'secret', 'user_id'];
}
