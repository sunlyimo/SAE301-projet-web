<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
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
    public function handle(Request $request, Closure $next) : Response
    {
        $exists = DB::table('vik_responsable_raid')
            ->where('UTI_ID', auth()->id())
            ->exists();
        Log::warning("id=".auth()->id());
        if (!$exists) {
            if($exists = DB::table('vik_administrateur')
                ->where('UTI_ID', auth()->id())
                ->exists()) {
            return $next($request);
            }
            abort(404);
        }
        return $next($request);
    }
}
