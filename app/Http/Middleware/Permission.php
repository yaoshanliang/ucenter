<?php
namespace App\Http\Middleware;

use Closure;
use Session;
use Config;
use Auth;
use Request;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->can('create-app')) {
            // dd(1);
        }
        $currentRole = Session::get('current_role');
        if (!in_array($currentRole['name'], Config::get('entrust.admin_role'))) {
            if (!Request::is('admin/forbidden')) {
                return redirect('/admin/forbidden');
            }
        }
        return $next($request);
    }
}
