<?php
namespace App\Model;

use App\Model\BaseModel as Model;

class UserFields extends Model
{
    protected $table = 'user_fields';
    protected $fillable = ['name', 'title', 'type', 'validation', 'description'];
}
