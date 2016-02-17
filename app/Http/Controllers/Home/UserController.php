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
use App\Http\Requests\UserFieldsRequest;

class UserController extends Controller
{
    public function index()
    {
        $user = Cache::get(Config::get('cache.users') . Auth::id());
        return view('home.user.index')->withUser($user);
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
}
