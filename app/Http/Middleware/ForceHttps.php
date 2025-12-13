<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya force HTTPS untuk akses dari domain publik
        if ($request->getHost() === 'draft-web.purido.xyz' && !$request->secure()) {
            return redirect()->secure($request->getRequestUri(), 301);
        }
        
        return $next($request);
    }
}