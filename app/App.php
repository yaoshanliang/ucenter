<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class App extends Model {
	protected $table = 'apps';

	use SoftDeletes;
	protected $dates = ['deleted_at'];
}
