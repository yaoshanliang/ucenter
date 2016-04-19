<?php
namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\Model;

class Setting extends Model {
	protected $table = 'settings';

	protected $fillable = ['id', 'name', 'value', 'description', 'type', 'order'];
	use SoftDeletes;
	protected $dates = ['deleted_at'];
}
