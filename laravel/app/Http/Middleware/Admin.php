<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $exists = DB::table('vik_administrateur')
                    ->where('UTI_ID', auth()->id())
                    ->exists();

        if (!$exists) {
            abort(404);
        }
        return $next($request);
    }
}
