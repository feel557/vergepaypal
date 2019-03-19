<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;

class Common
{
    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handle($request, Closure $next)
	{

	if($request->user()){

	if( $request->user()->user_type == 2 OR $request->user()->user_type == 21){
	return $next($request);
	}

	return redirect('/');
	}


	return redirect('/');

	}


}