<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
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
        $rai_id = $request->route('rai_id');
        $cou_id = $request->route('cou_id');

        // Vérifier si l'utilisateur est responsable du raid de cette course
        $isRaidResponsable = DB::table('vik_raid')
            ->where('RAI_ID', $rai_id)
            ->where('UTI_ID', auth()->id())
            ->exists();

        // Vérifier si l'utilisateur est le responsable direct de cette course
        $isCourseResponsable = DB::table('vik_course')
            ->where('RAI_ID', $rai_id)
            ->where('COU_ID', $cou_id)
            ->where('UTI_ID', auth()->id())
            ->exists();

        // Vérifier si l'utilisateur est administrateur
        $isAdmin = DB::table('vik_administrateur')
            ->where('UTI_ID', auth()->id())
            ->exists();

        Log::warning("User ID: " . auth()->id() . ", RAI_ID: $rai_id, COU_ID: $cou_id, isRaidResponsable: $isRaidResponsable, isCourseResponsable: $isCourseResponsable, isAdmin: $isAdmin");

        if (!$isCourseResponsable && !$isRaidResponsable && !$isAdmin) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette course.');
        }

        return $next($request);
    }
}
