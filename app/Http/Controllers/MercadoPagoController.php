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

        $paymentMethodId = $request['paymentMethodId'];

        if ($paymentMethodId === 'pix') {
            // Dados para pagamento via PIX
            $data['transaction_amount'] = (float)$request['transactionAmount'];
            $data['payment_method_id'] = 'pix';
            $data['description'] = $request['description'];
            $data['payer']['email'] = $request['payer']['email'];

            // Pix não usa token, parcelas nem installments
        } else {
            // Dados para pagamento com cartão (fluxo atual)
            $data['transaction_amount'] = (float)$request['transactionAmount'];
            $data['token'] = $request['token'];
            $data['description'] = $request['description'];
            $data['installments'] = (int)$request['installments'];
            $data['payment_method_id'] = $paymentMethodId;
            $data['payer']['email'] = $request['payer']['email'];
        }

        $request_options = new RequestOptions();
        $uniqueId = uniqid();
        $request_options->setCustomHeaders([
            "X-Idempotency-Key: {$uniqueId}"
        ]);

        try {
            $payment = $client->create($data, $request_options);

            $response = [
                'status' => $payment->status,
                'status_detail' => $payment->status_detail,
                'id' => $payment->id,
            ];

            // Se for PIX, inclui o QR code e código de pagamento
            if ($paymentMethodId === 'pix' && isset($payment->point_of_interaction)) {
                $response['qr_code'] = $payment->point_of_interaction->transaction_data->qr_code;
                $response['qr_code_base64'] = $payment->point_of_interaction->transaction_data->qr_code_base64;
            }
        } catch (MPApiException $e) {
            $response['error'] = $e->getApiResponse()->getContent();
        } catch (Exception $e) {
            $response['error'] =  $e->getMessage();
        }

        if (data_get($response, 'error.message', null)) {
            $response['error'] = data_get($response, 'error.message', null);
            return response()->json($response);
        }

        if ($payment->status == 'approved') {
            $paymentInfo = $this->paymentRequest::where(['id' => $request['payment_id']])->first();
            if ($paymentInfo) {
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
    
    public function webhook(Request $request)
    {
        $topic = $request->input('topic'); // geralmente 'payment'
        $id = $request->input('id');       // id do pagamento

        if ($topic === 'payment' && $id) {
            MercadoPagoConfig::setAccessToken($this->config->access_token);
            $client = new PaymentClient();

            try {
                // Busca o pagamento atualizado no Mercado Pago
                $payment = $client->get($id);

                // Busca o pagamento local no banco de dados
                $paymentRequest = $this->paymentRequest::where('transaction_id', $payment->id)->first();

                if ($paymentRequest) {
                    $isPaid = $payment->status === 'approved' ? 1 : 0;

                    $paymentRequest->update([
                        'is_paid' => $isPaid,
                        'payment_status' => $payment->status,
                        'status_detail' => $payment->status_detail,
                    ]);

                    // Dispara hooks personalizados, se existirem
                    if ($isPaid && function_exists($paymentRequest->success_hook)) {
                        call_user_func($paymentRequest->success_hook, $paymentRequest);
                    } elseif (!$isPaid && function_exists($paymentRequest->failure_hook)) {
                        call_user_func($paymentRequest->failure_hook, $paymentRequest);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Erro no webhook Mercado Pago: ' . $e->getMessage());
                return response()->json(['error' => 'Erro interno'], 500);
            }
    }

    return response()->json(['status' => 'ok']);
    }
}
