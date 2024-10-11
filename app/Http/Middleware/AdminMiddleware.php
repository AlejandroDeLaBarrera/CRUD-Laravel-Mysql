<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware {

    public function handle($request, Closure $next) {

        if(Auth::check() && Auth::user()->profile_id == 1) {
            return $next($request);  //si es admin, continua
        }

        return redirect('/'); //si no es admin, redirige

    }
}
