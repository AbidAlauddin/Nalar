<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTeacher
{
    public const ROLE = User::ROLE_TEACHER;

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        abort_unless($user && $user->isTeacher(), 403, 'Anda tidak memiliki akses ke halaman ini.');

        return $next($request);
    }
}
