<?php

namespace Mediacity\Installer\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsInstalled {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(config('installer.install_status') == 1){

            return $next($request);

        }else{

            return redirect()->route('eulaterm');

        }
    }
    
}