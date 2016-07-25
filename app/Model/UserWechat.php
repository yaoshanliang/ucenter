<?php
namespace App\Model;

use App\Model\BaseModel as Model;

class UserWechat extends Model
{
    protected $table = 'user_wechat';
    protected $fillable = ['user_id', 'unionid', 'openid', 'nickname', 'sex', 'language', 'city', 'province', 'country', 'headimgurl'];
}
