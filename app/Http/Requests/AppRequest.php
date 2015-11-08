<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\App;
use Auth;

class AppRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		$id = $this->route('app');
		return App::where('id', $id)->where('user_id', Auth::id())->exists();
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			// 'name' => 'required|unique:apps',
			'title' => 'required',
			'home_url' => 'required|url',
			'login_url' => 'required|url',
			'secret' => 'required'
		];
	}

}
