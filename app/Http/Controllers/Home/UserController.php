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

class UserController extends Controller
{
    public function index()
    {
        $user = Cache::get(Config::get('cache.users') . Auth::id());
        return view('home.user.index')->withUser($user);
    }

    public function edit()
    {
        $user = Cache::get(Config::get('cache.users') . Auth::id());
        return view('home.user.edit')->withUser($user);
    }

    public function update(Request $request)
    {
        $user = Cache::get(Config::get('cache.users') . Auth::id());
        foreach ($user['details'] as $k => $v) {
            if ($v['value'] != $request->$k) {
                $fieldId = UserFields::where('name', $k)->first(array('id'))->toArray();
                $result = UserInfo::where('user_id', Auth::id())->where('field_id', $fieldId['id'])->update(array('value' => $request->$k));
                $isEdit = true;
            }
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
