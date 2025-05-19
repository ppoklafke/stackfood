<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\CashBack;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CashBackController extends Controller
{
    public function getCashback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $customer_id=Auth::user()?->id ?? $request->customer_id ?? 'all';
        return  Helpers::getCalculatedCashBackAmount(amount:$request->amount, customer_id:$customer_id);
    }
    public function list(){
        $customer_id=Auth::user()?->id ?? request()?->customer_id ?? 'all';
        $data =CashBack::active()
        ->Running()
        ->where(function($query)use($customer_id){
            $query->whereJsonContains('customer_id', [(string) $customer_id])->orWhereJsonContains('customer_id', ['all']);
        })
        ->when(is_numeric($customer_id), function($q) use ($customer_id){
            $q->where('same_user_limit', '>', function($query) use ($customer_id) {
                $query->select(DB::raw('COUNT(*)'))
                        ->from('cash_back_histories')
                        ->where('user_id', $customer_id)
                        ->whereColumn('cash_back_id', 'cash_backs.id');
                });
            })
        ->orderBy('cashback_amount','desc')->get();
        return response()->json($data, 200);
    }

}
