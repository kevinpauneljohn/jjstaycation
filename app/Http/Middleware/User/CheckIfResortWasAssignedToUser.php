<?php

namespace App\Http\Middleware\User;

use Closure;
use Illuminate\Http\Request;

class CheckIfResortWasAssignedToUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $resortId =  $request->segment(2);
        if(!auth()->user()->hasRole('super admin'))
        {
            if(auth()->user()->stayCationLists->where('id',$resortId)->count() < 1)
            {
                return abort(404);
            }
        }
        return $next($request);
    }
}
