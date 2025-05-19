<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\CentralLogics\Helpers;

class Subscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $module)
    {
        if (auth('vendor_employee')->check() || auth('vendor')->check()) {
            $restaurant = Helpers::get_restaurant_data();
            if ($restaurant->restaurant_model == 'commission') {
                return $next($request);
            } elseif ($restaurant->restaurant_model == 'unsubscribed') {
                Toastr::error(translate('messages.your_subscription_is_expired.You_can_only_process_your_on_going_orders.'));
                return back();
            } elseif ($restaurant->restaurant_model == 'none') {
                Toastr::error(translate('Please_chose_a_business_plan_to_continue_your_services'));
                return back();
            } elseif ($restaurant->restaurant_model == 'subscription') {
                if ($restaurant->restaurant_sub == null) {
                    Toastr::error(translate('messages.you_are_not_subscribed_to_any_package'));
                    return back();
                } else {
                    $restaurant_sub = $restaurant?->restaurant_sub;

                    $modulePermissons = [
                        'reviews' => $restaurant_sub?->review,
                        'pos' => $restaurant_sub?->pos,
                        'deliveryman' => $restaurant_sub?->self_delivery,
                        'chat' => $restaurant_sub?->chat,
                    ];
                    if (in_array($module, ['reviews', 'pos', 'deliveryman', 'chat'])) {
                        if ($modulePermissons[$module] == 1) {
                            return $next($request);
                        } else {
                            Toastr::error(translate('messages.your_package_does_not_include_this_section'));
                            return back();
                        }
                    }
                }
            }
        }
        return $next($request);
    }
}
