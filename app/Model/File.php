<?php
namespace App\Model;

use App\Model\Model;

class File extends Model
{
	protected $table = 'files';

	protected $fillable = ['id', 'app_id', 'user_id', 'file_name', 'file_path', 'extension', 'mime_type', 'size', 'created_at'];

	public $timestamps = false;

}
