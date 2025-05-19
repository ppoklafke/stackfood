<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use App\CentralLogics\Helpers;
class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $lang ='en';
        $direction ='ltr';
        try {
            $language =Helpers::getSettingsDataFromConfig(settings: 'system_language');
            if($language){
                foreach (json_decode($language->value, true) as $key => $data) {
                    if ($data['default'] == true) {
                        $lang= $data['code'];
                        $direction= $data['direction'];
                    }
                }
            }
        } catch (\Exception $exception) {
            info($exception->getMessage());
        }

        if ($request->is('restaurant-panel*')) {
            if (session()->has('vendor_local')) {
                App::setLocale(session()->get('vendor_local'));
            }
            else{
                session()->put('vendor_site_direction', $direction);
                App::setLocale($lang);
            }


        }elseif($request->is('admin*')){
            if (session()->has('local')) {
                App::setLocale(session()->get('local'));
            }
            else{
                session()->put('site_direction', $direction);
                App::setLocale($lang);
            }
        }else{
            if (session()->has('landing_local')) {
                App::setLocale(session()->get('landing_local'));
            }else{
                session()->put('landing_site_direction', $direction);
                App::setLocale($lang);

            }
        }
        return $next($request);
    }
}
