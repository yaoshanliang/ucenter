<?php
namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Model\Permission;
use Auth;
use Session;
use Response;

class PermissionRequest extends Request
{
    private $id;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->id = is_null($this->route('id')) ? $this->route('permission') : $this->route('id');
        if ($this->id) {
            return Permission::where('id', $this->id)
                ->where('app_id', Session::get('current_app_id'))
                ->exists();
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
