<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Role
{
    public function handle(Request $request, Closure $next, $role)
    {
        $accepted = [
            'admin'  => ['admin', 'editor', 'client'],
            'editor' => ['editor', 'client'],
            'client' => ['client'],
        ];

        $userRole = Auth::user()->role;

        if (!in_array($role, $accepted[$userRole])) {
            $message = 'You don\'t have permission to make this operation';
            if ($request->expectsJson()) {
                return response()->make(['error' => [$message]], 403);
            } else {
                throw new AuthorizationException();
            }
        }

        return $next($request);
    }
}
