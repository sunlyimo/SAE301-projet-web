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
        $userId = auth()->id();
        $raiId = $request->input('RAI_ID') ?? $request->route('rai_id');
        if ($raiId) {
            $isRaidOwner = DB::table('vik_raid')
                ->where('RAI_ID', $raiId)
                ->where('UTI_ID', $userId)
                ->exists();
            if (!$isRaidOwner) {
                return redirect('/')->with('error', "Vous n'avez pas les droits sur ce raid.");
            }
        }
        return $next($request);
    }
}
