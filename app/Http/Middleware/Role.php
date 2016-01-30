<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Request;
use Session;
use Config;

class Role
{
    protected $auth;

    /**
     * Creates a new instance of the middleware.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Closure $next
     * @param  $roles
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $currentRole = Session::get('current_role');
        if (!in_array($currentRole['name'], Config::get('entrust.admin_role'))) {
            if (!Request::is('admin/forbidden')) {
                return redirect('/admin/forbidden');
            }
        }
        return $next($request);
    }

}
