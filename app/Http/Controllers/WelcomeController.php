<?php
namespace App\Http\Controllers;

use Auth;
use Session;

class WelcomeController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Welcome Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders the "marketing page" for the application and
    | is configured to only allow guests. Like most of the other sample
    | controllers, you are free to modify or remove it as you desire.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function index()
    {
        $roles = Session::get('roles');
        foreach ($roles as $key => $value) {
            foreach ($value as $k => $v) {
                if ($v['name'] == 'developer') {
                    return redirect('/admin/index');
                }
            }
        }
        return redirect('/home');
    }
}
