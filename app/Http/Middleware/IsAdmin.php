<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập để truy cập trang admin');
        }

        // Check if user is admin
        $user = auth()->user();
        if (!$user instanceof User || !$user->isAdmin()) {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập trang admin');
        }

        return $next($request);
    }
}
