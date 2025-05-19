<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\Processor;
use Illuminate\Http\Request;
use App\Models\PaymentRequest;
use Exception;
use Illuminate\Support\Facades\Validator;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Common\RequestOptions;

class MercadoPagoController extends Controller
{
    use Processor;

    private PaymentRequest $paymentRequest;
    private $config;
    private $user;

    public function __construct(PaymentRequest $paymentRequest, User $user)
    {
        $config = $this->payment_config('mercadopago', 'payment_config');
        if (!is_null($config) && $config->mode == 'live') {
            $this->config = json_decode($config->live_values);
        } elseif (!is_null($config) && $config->mode == 'test') {
            $this->config = json_decode($config->test_values);
        }
        $this->paymentRequest = $paymentRequest;
        $this->user = $user;
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_id' => 'required|uuid'
        ]);

        if ($validator->fails()) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_400, null, $this->error_processor($validator)), 400);
        }

        $data = $this->paymentRequest::where(['id' => $request['payment_id']])->where(['is_paid' => 0])->first();
        if (!isset($data)) {
            return response()->json($this->response_formatter(GATEWAYS_DEFAULT_204), 200);
        }
        $config = $this->config;
        return view('payment-views.payment-view-marcedo-pogo', compact('config', 'data'));
    }
    public function make_payment(Request $request)
    {
        MercadoPagoConfig::setAccessToken($this->config->access_token);
        $client = new PaymentClient();
        $data = [];
        $data['transaction_amount'] = (float)$request['transactionAmount'];
        $data['token'] = $request['token'];
        $data['description'] = $request['description'];
        $data['installments'] = (int)$request['installments'];
        $data['payment_method_id'] = $request['paymentMethodId'];
        $data['payer']['email'] = $request['payer']['email'];


        $request_options = new RequestOptions();
            $unigueId=uniqid();
            $request_options->setCustomHeaders([
                "X-Idempotency-Key: {$unigueId}"
            ]);

        try {
            $payment = $client->create($data, $request_options);
            $response = array(
                'status' => $payment->status,
                'status_detail' => $payment->status_detail,
                'id' => $payment->id
            );

        } catch (MPApiException $e) {
            $response['error'] = $e->getApiResponse()->getContent();
        } catch (Exception $e) {
            $response['error'] =  $e->getMessage();
        }


        if(data_get($response,'error.message',null)){

            $response['error'] =  data_get($response,'error.message',null);
            return response()->json($response);
        }


        if ($payment->status == 'approved') {
            $paymentInfo = $this->paymentRequest::where(['id' => $request['payment_id']])->first();
            if($paymentInfo){
                $paymentInfo->transaction_id = $payment->id;
                $paymentInfo->save();
            }
        }

        return response()->json($response);
    }

    public function get_test_user(Request $request)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api.mercadopago.com/users/test_user");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->config->access_token
        ));
        curl_setopt($curl, CURLOPT_POSTFIELDS, '{"site_id":"MLA"}');
        $response = curl_exec($curl);
    }

    public function success(Request $request)
    {
        $paymentData = $this->paymentRequest::where(['id' => $request['payment_id']])->first();
        if($paymentData->transaction_id != null){
            $this->paymentRequest::where(['id' => $request['payment_id']])->update([
                'payment_method' => 'mercadopago',
                'is_paid' => 1,
            ]);
            $data = $this->paymentRequest::where(['id' => $request['payment_id']])->first();
            if (isset($data) && function_exists($data->success_hook)) {
                call_user_func($data->success_hook, $data);
            }
            return $this->payment_response($data, 'success');
        }else{
            $paymentData = $this->paymentRequest::where(['id' => $request['payment_id']])->first();
            if (isset($paymentData) && function_exists($paymentData->failure_hook)) {
                call_user_func($paymentData->failure_hook, $paymentData);
            }
            return $this->payment_response($paymentData, 'fail');
        }
    }

    public function failed(Request $request)
    {
        $paymentData = $this->paymentRequest::where(['id' => $request['payment_id']])->first();
        if (isset($paymentData) && function_exists($paymentData->failure_hook)) {
            call_user_func($paymentData->failure_hook, $paymentData);
        }
        return $this->payment_response($paymentData, 'fail');
    }
}
