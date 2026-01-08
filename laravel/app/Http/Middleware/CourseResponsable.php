<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class CourseResponsable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $userId = auth()->id();
        $raiId = $request->route('rai_id');
        $couId = $request->route('cou_id');
        $isOwner = DB::table('vik_course')
            ->where('RAI_ID', $raiId)
            ->where('COU_ID', $couId)
            ->where('UTI_ID', $userId)
            ->exists();
        $isRaidOwner = DB::table('vik_raid')
            ->where('RAI_ID', $raiId)
            ->where('UTI_ID', $userId)
            ->exists();
        if ($isOwner || $isRaidOwner) {
            return $next($request);
        }
        abort(404);
    }
}
