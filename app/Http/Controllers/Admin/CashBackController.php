<?php

namespace App\Http\Controllers\Admin;

use App\Models\CashBack;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Requests\CashBackAddRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\CashBackUpdateRequest;

class CashBackController extends Controller
{

    public function index(?Request $request): View|Collection|LengthAwarePaginator|null
    {
        $key = explode(' ', $request['search']);

        $cashbacks= CashBack::where(function ($query) use ($key) {
            foreach ($key as $value) {
                $query->orWhere('title', 'like', "%{$value}%");
            }
        })->CashBackType($request['cashback_type'])->latest('end_date')->paginate(config('default_pagination'));

        $language = getWebConfig('language');
        $defaultLang = str_replace('_', '-', app()->getLocale());
        return view('admin-views.promotions.cashback.index', compact('cashbacks','language','defaultLang'));
    }



    public function add(CashBackAddRequest $request): RedirectResponse
    {
        $customerId  = $request->customer_id ?? ['all'];
        $cashback = CashBack::create([
            "title" => $request->title[array_search('default', $request->lang)],
            'customer_id' =>  json_encode($customerId),
            "cashback_type" => $request->cashback_type,
            "same_user_limit" => $request->same_user_limit,
            "cashback_amount" => $request->cashback_amount,
            "min_purchase" => $request->min_purchase != null ? $request->min_purchase : 0,
            "max_discount" => $request->max_discount != null ? $request->max_discount : 0,
            "start_date" => $request->start_date,
            "end_date" => $request->end_date,
        ]);


            Helpers::add_or_update_translations(request: $request, key_data:'title' , name_field:'title' , model_name: 'CashBack' ,data_id: $cashback->id,data_value: $cashback->title);


        Toastr::success(translate('messages.cashback_added_successfully'));
        return back();
    }


    public function getUpdateView(string|int $id): view
    {
        $cashback =CashBack::withoutGlobalScope('translate')->whereId($id)->first();
        $language = getWebConfig('language');
        $defaultLang = str_replace('_', '-', app()->getLocale());
        return view('admin-views.promotions.cashback.edit', compact('cashback','language','defaultLang'));
    }

    public function update(CashBackUpdateRequest $request, $id): RedirectResponse
    {
        $customerId  = $request->customer_id ?? ['all'];

        $cashback=CashBack::updateOrCreate([
            'id'=> $id
            ],[
                "title" => $request->title[array_search('default', $request->lang)],
                'customer_id' =>  json_encode($customerId),
                "cashback_type" => $request->cashback_type,
                "same_user_limit" => $request->same_user_limit,
                "cashback_amount" => $request->cashback_amount,
                "min_purchase" => $request->min_purchase != null ? $request->min_purchase : 0,
                "max_discount" => $request->max_discount != null ? $request->max_discount : 0,
                "start_date" => $request->start_date,
                "end_date" => $request->end_date,
            ]);

            Helpers::add_or_update_translations(request: $request, key_data:'title' , name_field:'title' , model_name: 'CashBack' ,data_id: $cashback->id,data_value: $cashback->title);

        Toastr::success(translate('messages.cashback_updated_successfully'));
        return back();
    }



    public function delete(Request $request): RedirectResponse
    {
        $cashback = CashBack::find($request['id']);
        $cashback->translations()?->delete();
        $cashback->delete();
        Toastr::success(translate('messages.cashback_deleted_successfully'));
        return back();
    }

    public function updateStatus(Request $request): RedirectResponse
    {
        CashBack::whereId($request['id'])->update([
            'status'=>$request['status']
        ]);
        Toastr::success( $request['status'] == 1 ?  translate('messages.Cashback_Successfully_Enabled') : translate('Cashback_Disabled') );
        return back();
    }

}
