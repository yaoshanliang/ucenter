<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Model\App;
use Auth;
use Response;

class AppRequest extends Request
{
    private $id;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->id = $this->route('id');
        if ($this->id) {
            return App::where('id', $this->id)->where('user_id', Auth::id())->exists();
        } else {
            return true;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
