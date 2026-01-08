<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class RaidResponsable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $exists = DB::table('vik_responsable_raid')
                    ->where('UTI_ID', auth()->id())
                    ->exists();
        if (!$exists) {
            return redirect('/')->with('error', "Accès réservé aux responsables de raid.");
        }
        return $next($request);
    }
}
