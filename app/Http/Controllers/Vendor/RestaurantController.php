<?php

namespace App\Http\Controllers\Vendor;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Restaurant;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RestaurantController extends Controller
{
    public function view()
    {
        $shop = Helpers::get_restaurant_data();
        return view('vendor-views.shop.shopInfo', compact('shop'));
    }

    public function edit()
    {
        $shop = Restaurant::withoutGlobalScope('translate')->with('translations')->find(Helpers::get_restaurant_id());
        return view('vendor-views.shop.edit', compact('shop'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:191',
            'address' => 'nullable|max:1000',
            'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9|max:20|unique:restaurants,phone,'.Helpers::get_restaurant_id(),
            'image' => 'nullable|max:2048',
            'photo' => 'nullable|max:2048',

        ], [
            'f_name.required' => translate('messages.first_name_is_required'),
        ]);

        if($request->name[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_restaurant_name_is_required'));
            return back();
        }
        if($request->address[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_restaurant_address_is_required'));
            return back();
        }

        $shop = Restaurant::findOrFail(Helpers::get_restaurant_id());
        $shop->name = $request->name[array_search('default', $request->lang)];
        $shop->address = $request->address[array_search('default', $request->lang)];
        $shop->phone = $request->contact;
        $shop->logo = $request->has('image') ? Helpers::update(dir: 'restaurant/',old_image:  $shop->logo ,format: 'png', image: $request->file('image')) : $shop->logo;
        $shop->cover_photo = $request->has('photo') ? Helpers::update(dir: 'restaurant/cover/',old_image:  $shop->cover_photo,  format:'png',image:  $request->file('photo')) : $shop->cover_photo;
        $shop?->save();
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach($request->lang as $index=>$key)
        {
            if($default_lang == $key && !($request->name[$index])){
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\Restaurant',
                            'translationable_id' => $shop->id,
                            'locale' => $key,
                            'key' => 'name'
                        ],
                        ['value' => $shop->name]
                    );
                }
            }else{

                if ($request->name[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        ['translationable_type'  => 'App\Models\Restaurant',
                            'translationable_id'    => $shop->id,
                            'locale'                => $key,
                            'key'                   => 'name'],
                            ['value'                 => $request->name[$index]]
                        );
                }
            }
            if($default_lang == $key && !($request->address[$index])){
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\Restaurant',
                            'translationable_id' => $shop->id,
                            'locale' => $key,
                            'key' => 'address'
                        ],
                        ['value' => $shop->address]
                    );
                }
            }else{

                if ($request->address[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        ['translationable_type'  => 'App\Models\Restaurant',
                        'translationable_id'    => $shop->id,
                        'locale'                => $key,
                        'key'                   => 'address'],
                        ['value'                 => $request->address[$index]]
                    );
                }
            }
        }
        if($shop?->vendor?->userinfo) {
            $userinfo = $shop->vendor->userinfo;
            $userinfo->f_name = $shop->name;
            $userinfo->image = $shop->logo;
            $userinfo?->save();
        }

        Toastr::success(translate('messages.restaurant_data_updated'));
        return redirect()->route('vendor.shop.view');
    }

    public function update_message(Request $request)
    {
        $request->validate([
            'announcement_message' => 'required|max:255',
        ]);
        $shop = Restaurant::findOrFail(Helpers::get_restaurant_id());
        $shop->announcement_message = $request->announcement_message;
        $shop->save();

        Toastr::success(translate('messages.restaurant_data_updated'));
        return redirect()->route('vendor.shop.view');
    }

    public function qr_view()
    {
        $restaurant = Helpers::get_restaurant_data();
        $data = json_decode($restaurant->qr_code, true);
        $qr = base64_encode(json_encode($data));
        $code = isset($data)?QrCode::size(180)->generate($data['website'].'?qrcode='.$qr):'';
        return view('vendor-views.shop.qrcode', compact('restaurant','data', 'code'));
    }
    public function qr_store(Request $request)
    {
        $restaurant = Helpers::get_restaurant_data();
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'phone' => 'required',
            'website' => 'required'
        ]);


        $data = [];

        $data['title'] = $request->title;
        $data['description'] = $request->description;
        $data['phone'] = $request->phone;
        $data['website'] = $request->website;

        $restaurant->qr_code = json_encode($data);
        $restaurant->save();

        Toastr::success(translate('updated successfully'));
        return back();

    }

    public function qr_pdf()
    {
        $restaurant = Helpers::get_restaurant_data();
        $data = json_decode($restaurant->qr_code, true);
        $code = isset($data)?QrCode::size(180)->generate(json_encode($data)):'';

        $pdf = PDF::loadView('vendor-views.shop.qrcode-pdf', compact('restaurant','data', 'code'))->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->download('qr-code' . rand(00001, 99999) . '.pdf');
    }

    public function qr_print()
    {
        $restaurant = Helpers::get_restaurant_data();
        $data = json_decode($restaurant->qr_code, true);
        $qr = base64_encode(json_encode($data));
        $code = isset($data)?QrCode::size(180)->generate($data['website'].'?qrcode='.$qr):'';
        return view('vendor-views.shop.qrcode-print', compact('restaurant','data', 'code'));
    }

}
