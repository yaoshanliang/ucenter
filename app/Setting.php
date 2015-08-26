<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model {
	protected $table = 'settings';

	use SoftDeletes;
	protected $dates = ['deleted_at'];
}
