<?php
namespace App\Model;

use App\Model\BaseModel as Model;

class Email extends Model
{
    protected $table = 'emails';

    protected $fillable = ['id', 'app_id', 'user_id', 'email', 'subject', 'content', 'pushed_at', 'poped_at', 'created_at'];

    public $timestamps = false;

}
