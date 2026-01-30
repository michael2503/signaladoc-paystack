<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\App;
use App\Models\Transaction;
use App\Models\WebhookEvent;
use App\Services\Paystack as ServicesPaystack;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiController extends Controller
{

    use ApiResponse;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        //
    }


    public function createApp(Request $request)
    {
        $data = $request->json()->all();
        $validator = Validator::make($data, [
            'name' => 'required',
            'paystack_public_key' => 'required',
            'paystack_secret_key' => 'required',
            'callback_url' => 'required',
            'webhook_secret' => 'required',
        ]);
        if ($validator->fails()) {
            $response = $validator->messages()->first();
            return $this->errorResponse($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $create = App::create($data);
        if($create){
            return $this->successResponse($create, Response::HTTP_CREATED);
        }
        return $this->errorResponse('Sorry the system was unable to process your request', Response::HTTP_UNPROCESSABLE_ENTITY);
    }



    public function initializePayment(Request $request)
    {
        $data = $request->json()->all();
        $validator = Validator::make($request->json()->all(), [
            'app_id' => 'required|integer',
            'email' => 'required|email',
            'amount' => 'required',
            'currency' => 'required',
            'reference' => 'required|unique:transactions',
        ]);
        if ($validator->fails()) {
            $response = $validator->messages()->first();
            return $this->errorResponse($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $app = App::find($data['app_id']);
        if($app){
            $paystack = new ServicesPaystack($app);
            $response = $paystack->initializePay($data);

            if($response['status']){
                $data['amount'] = $data['amount'] / 100;
                Transaction::create($data);

                $resp = [
                    'authorization_url' => $response['data']['authorization_url'],
                    'reference' => $response['data']['reference'],
                ];
                return $this->successResponse($resp, Response::HTTP_CREATED);
            }
            return $this->errorResponse($response['message'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $this->errorResponse('Sorry app with this ID does not exist', Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    public function callBackHandling($reference)
    {
        $transaction = Transaction::with("app:id,paystack_public_key,paystack_secret_key,callback_url")->where('reference', $reference)->first();

        if($transaction){
            $paystack = new ServicesPaystack($transaction['app']);
            $result = $paystack->verifyPay($reference);

            if ($result['status'] && $result['data']['status'] === 'success') {
                $transaction->update([
                    'status' => 'successful',
                    'paid_at' => now(),
                    'channel' => $result['data']['channel'],
                    'gateway_response' => $result['data'],
                ]);
            } else {
                $transaction->update([
                    'status' => 'failed',
                    'gateway_response' => $result['data'],
                ]);
            }

            $resp = $transaction['app']->callback_url . '?reference=' .$reference . '&status=' . $transaction->status;
            return $this->successResponse($resp, Response::HTTP_CREATED);

            // return redirect()->away(
            //     $transaction['app']->callback_url . '?' . http_build_query([
            //         'reference' => $reference,
            //         'status' => $transaction->status,
            //     ])
            // );
        } else {
            return $this->errorResponse('Sorry reference number doex not exist', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }


    public function webhookProcess(Request $request)
    {
        $data = $request->json()->all();
        $signature = $request->header('x-paystack-signature');
        $payload = $request->getContent();

        $reference = $data['data']['reference'];
        $transaction = Transaction::with("app:id,paystack_public_key,paystack_secret_key,callback_url")->where('reference', $reference)->first();
        // return hash_hmac('sha512', $payload, $transaction['app']['paystack_secret_key']);
        if($transaction){
            if ($signature == hash_hmac('sha512', $payload, $transaction['app']['paystack_secret_key'])) {
                $getEvent = $data['event'];
                $getEventID = $data['data']['id'];

                // check Idempotency
                if (WebhookEvent::where('event_id', $getEventID)->exists()) {
                    return $this->errorResponse('Webhook already existed', Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                WebhookEvent::create([
                    'event_id' => $getEventID,
                    'payload' => json_encode($data),
                ]);

                if ($getEvent === 'charge.success') {
                    $transaction->update([
                        'status' => 'successful',
                        'paid_at' => now(),
                        'channel' => $data['data']['channel'],
                        'raw_payload' => $data,
                    ]);
                }

                if ($getEvent === 'charge.failed') {
                    $transaction->update([
                        'status' => 'failed',
                        'raw_payload' => $data,
                    ]);
                }

                return $this->successResponse('success', Response::HTTP_CREATED);
            }
            return $this->errorResponse('x-paystack-signature does not matched', Response::HTTP_UNPROCESSABLE_ENTITY);

        }
        return $this->errorResponse('Sorry transaction does not exist', Response::HTTP_UNPROCESSABLE_ENTITY);
    }




}
