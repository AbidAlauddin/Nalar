<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    public const ROLE = User::ROLE_SUPER_ADMIN;

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        abort_unless($user && $user->isSuperAdmin(), 403, 'Anda tidak memiliki akses ke halaman ini.');

        return $next($request);
    }
}
