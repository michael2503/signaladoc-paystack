<?php

namespace App\Services;

use App\Models\App;
use Illuminate\Support\Facades\Http;

class Paystack
{

	/**
	 * Build success response
	 * @param string/array $data
	 * @param int  $code
	 * @return Illuminate\Http\JsonResponse
	*/

    public function __construct(
        protected App $app
    ) {}

    public static $liveBaseUrl = 'https://api.paystack.co';
    public static $testBaseUrl = 'https://api.paystack.co'; //sandbox url
    public static $timeout = '30';

    protected function client()
    {
        if($this->app->environment == 'live'){
            return Http::withToken($this->app->paystack_secret_key)
                ->baseUrl(self::$liveBaseUrl)
                ->timeout(self::$timeout);
        } else {
            return Http::withToken($this->app->paystack_secret_key)
                ->baseUrl(self::$testBaseUrl)
                ->timeout(self::$timeout);
        }
    }

    public function initializePay(array $data)
    {
        return $this->client()->post('/transaction/initialize', [
            'email' => $data['email'],
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'reference' => $data['reference'],
            'callback_url' => $this->app->callback_url,
            'metadata' => $data['metadata'] ?? [],
        ])->json();
    }


    public function verifyPay(string $reference)
    {
        return $this->client()
            ->get("/transaction/verify/{$reference}")
            ->json();
    }




}
