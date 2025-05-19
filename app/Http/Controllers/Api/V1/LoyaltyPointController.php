<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\CustomerLogic;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\LoyaltyPointTransaction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class LoyaltyPointController extends Controller
{
    public function point_transfer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'point' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if($request?->user()?->loyalty_point <= 0 || $request?->user()?->loyalty_point < (int)BusinessSetting::where('key','loyalty_point_minimum_point')->first()?->value || $request->point > $request?->user()?->loyalty_point) {
            return response()->json(['errors' => [ ['code' => 'point', 'message' => trans('messages.insufficient_point')]]], 203);
        }

        try
        {
            DB::beginTransaction();
            $wallet_transaction = CustomerLogic::create_wallet_transaction(user_id:$request?->user()?->id,amount:$request->point,transaction_type:'loyalty_point',referance:$request->reference);
            CustomerLogic::create_loyalty_point_transaction(user_id:$request?->user()?->id, referance:$wallet_transaction->transaction_id, amount:$request->point, transaction_type:'point_to_wallet');
            $notification_status= Helpers::getNotificationStatusData('customer','customer_add_fund_to_wallet');
            Helpers::add_fund_push_notification($request?->user()?->id, $wallet_transaction?->credit);

            if($notification_status?->mail_status == 'active' &&  config('mail.status') && $request?->user()?->email && Helpers::get_mail_status('add_fund_mail_status_user') =='1') {
                Mail::to($request->user()->email)->send(new \App\Mail\AddFundToWallet($wallet_transaction));
                }
            DB::commit();
            return response()->json(['message' => translate('messages.point_to_wallet_transfer_successfully')], 200);
        }catch(\Exception $ex){
            DB::rollBack();
            info($ex->getMessage());
        }

        return response()->json(['errors' => [ ['code' => 'customer_wallet', 'message' => translate('messages.failed_to_transfer')]]], 203);
    }

    public function transactions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $paginator = LoyaltyPointTransaction::where('user_id', $request?->user()?->id)->latest()->paginate($request->limit, ['*'], 'page', $request->offset);

        $data = [
            'total_size' => $paginator->total(),
            'limit' => $request->limit,
            'offset' => $request->offset,
            'data' => $paginator->items()
        ];
        return response()->json($data, 200);
    }
}
