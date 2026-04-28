<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DosenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    if (auth()->user()->role === 'dosen') {
        return $next($request);
    }
    return $this->redirectByRole(auth()->user()->role);
}

private function redirectByRole(string $role): Response
{
    return match($role) {
        'kepegawaian' => redirect()->route('kepegawaian_dashboard'),
        'comite'      => redirect()->route('comite_dashboard'),
        'senat'       => redirect()->route('senat_dashboard'),
        'superadmin'  => redirect()->route('superadmin_dashboard'),
        default       => redirect()->route('login.form'),
    };
}
}