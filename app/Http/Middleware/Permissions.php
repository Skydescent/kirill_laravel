<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Permissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        $user = $request->user();

        if (!$user->isAdmin()) {
            flash("Доступ запрещён", "danger");
            return redirect()->route("posts.index");
        }

        return $next($request);
    }
}
