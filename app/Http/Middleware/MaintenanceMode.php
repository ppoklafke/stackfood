<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\CentralLogics\Helpers;

class MaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Cache::has('maintenance')) {
            $maintenance = Cache::get('maintenance');

            if ($maintenance['restaurant_panel']) {
                if ($maintenance['maintenance_duration'] == 'until_change') {
                    return to_route('maintenance_mode');
                    } else {
                        if (isset($maintenance['start_date']) && isset($maintenance['end_date'])) {
                            $start = Carbon::parse($maintenance['start_date']);
                            $end = Carbon::parse($maintenance['end_date']);
                            $today = Carbon::now();
                            if ($today->between($start, $end)) {
                                return to_route('maintenance_mode');
                            }
                    }
                }
            }
        }


        return $next($request);
    }
}
