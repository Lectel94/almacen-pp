<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleNotInvited
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->hasRole('Guest')) {
            /*  auth()->logout(); */
             Auth::guard('web')->logout();
            // Redirige o muestra un error
            return redirect('/login')->with('message', 'Debe esperar a que un administrador le asigne una categoría. Le notificaremos al correo con el cual se registró.');
        }
        return $next($request);
    }


}
