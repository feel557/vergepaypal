<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;

class Admin
{
    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handle($request, Closure $next)
	{

	if($request->user()){

	if( $request->user()->admin == 1 ){
	return $next($request);
	}

	return redirect('/');
	}


	return redirect('/');

	}


}