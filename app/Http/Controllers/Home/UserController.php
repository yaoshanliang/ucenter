<?php
namespace App\Http\Controllers\Home;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\User;
use Auth;
use Cache;
use Config;
use Illuminate\Http\Request;
use App\Model\UserFields;
use App\Model\UserInfo;
use App\Model\UserWechat;
use App\Http\Requests\UserFieldsRequest;
use EasyWeChat\Foundation\Application;

class UserController extends Controller
{
    public function index(Application $wechat)
    {
        $user = Cache::get(Config::get('cache.users') . Auth::id());
        $wechat->oauth->redirect();
        $wechat = Cache::get(Config::get('cache.wechat.openid') . (Cache::get(Config::get('cache.wechat.user_id') . Auth::id())));
        return view('home.user.index')->withUser($user)->withWechat($wechat);
    }

    // 编辑个人信息
    public function edit()
    {
        $user = Cache::get(Config::get('cache.users') . Auth::id());
        return view('home.user.edit')->withUser($user);
    }

    public function update(Request $request)
    {
        // 验证
        $userFieldsArray = UserFields::where('validation', '<>', '')->get(array('name', 'validation'))->toArray();
        if (!empty($userFieldsArray)) {
            foreach ($userFieldsArray as $v) {
                $userFields[$v['name']] = $v['validation'];
            }
            $this->validate($request, $userFields);
        }

        // 更新数据库
        $user = Cache::get(Config::get('cache.users') . Auth::id());
        foreach ($user['details'] as $k => &$v) {
            if ($v['value'] != $request->$k) {
                $fieldId = UserFields::where('name', $k)->first(array('id'))->toArray();
                $result = UserInfo::where('user_id', Auth::id())->where('field_id', $fieldId['id'])->update(array('value' => $request->$k));
                $v['value'] = $request->$k;
                $isEdit = true;
            }
        }

        // 更新cache
        if (isset($isEdit)) {
            Cache::forever(Config::get('cache.users') . Auth::id(), $user);
        }

        if (isset($result) && $result) {
            session()->flash('success_message', '个人信息编辑成功');
            return redirect('/home/user/edit');
        } elseif (!isset($isEdit)) {
            session()->flash('success_message', '未做修改');
            return redirect('/home/user/edit');
        } else {
            return redirect()->back()->withInput()->withErrors('保存失败！');
        }
    }

    // 微信扫描绑定之后的回调
    public function wechatCallback(Application $wechat)
    {
        $wechatUser = $wechat->oauth->user()->toArray();
        $wechatUser = $wechatUser['original'];
        $data = array('user_id' => Auth::id(),
            'unionid' => $wechatUser['unionid'],
            'openid' => $wechatUser['openid'],
            'nickname' => $wechatUser['nickname'],
            'sex' => $wechatUser['sex'],
            'language' => $wechatUser['language'],
            'city' => $wechatUser['city'],
            'province' => $wechatUser['province'],
            'country' => $wechatUser['country'],
            'headimgurl' => $wechatUser['headimgurl'],
        );

        // 已绑定则修改，未绑定则新增
        $exists = UserWechat::where('user_id', Auth::id())->exists();
        if ($exists) {
            UserWechat::where('user_id', Auth::id())->update($data);
        } else {
            UserWechat::create($data);
        }

        // 更新cache
        Cache::forever(Config::get('cache.wechat.openid') . $data['openid'], $data);
        Cache::forever(Config::get('cache.wechat.user_id') . $data['user_id'], $data['openid']);

        session()->flash('success_message', '绑定成功');
        return redirect('/home/user');
    }
}
