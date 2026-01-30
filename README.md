Centralized Paystack Payment Gateway API Using Laravel Framework

This is a centralized Paystack payment gateway service that allows multiple Laravel applications to initialize, verify, and reconcile payments securely using a shared service. Each application has its own Paystack public & secret keys, redirect (callback) URL and webhook endpoint by creating an app. The features includes:
- Idempotent webhook processing
- Environment-aware (test & live)
- Secure webhook handling with signature verification
- Multi-tenant Paystack support
- Redirect-based payment initialization
- Transaction reconciliation via redirect + webhook

The technical constraints used in this system are:
- Laravel 10.8
- PHP 8.1
- MySQL
- Paystack RESTFUL API
- Laravel’s HTTP Client

The database schema/migrations used in this system includes:
- Apps table: App table is created to store Paystack credentials and its environment. The columns includes: name (app name), paystack_public_key, paystack_secret_key, callback_url, webhook_secret and environment (live or test)
- Transactions table: The transaction table is used to store all payment initiated and also use to track the payment status. The columns include: app_id, reference, amount, currency, status, gateway_response, paid_at, channel and raw_payload.
- Webhook Events table: The webhook event is used to ensure webhook idempotency. The columns include: event_id and payload

API Endpoints
1.  Create App
    - Request: Post
    - Endpoint: {{baseUrl}}/api/app/create
    - Payload: name, paystack_public_key, paystack_secret_key, callback_url, webhook_secret and environment
    SAMPLE PAYLOAD
    {
    	"name": "Signal A Doctor",
    	"paystack_public_key": "pk_test_d929bbd3b478f8fe2b0ac9fa6b368d5b52eeceb5",
    	"paystack_secret_key": "sk_test_efd2fbaddd92cbaa96391a926e7ab51b7469eea6",
    	"callback_url": "https://signaldoctor.com",
    	"webhook_secret": "https://signaldoctor.com/test-hook",
    	"environment": "test" //live or test
    }

    Note: All fields are required.

    SAMPLE RESPONSE
    {
    	"status": "success",
    	"code": 201,
    	"data": {
            "name": "Signal A Doctor",
            "paystack_public_key": "pk_test_d929bbd3b478f8fe2b0ac9fa6b368d5b52eeceb5",
            "paystack_secret_key": "sk_test_efd2fbaddd92cbaa96391a926e7ab51b7469eea6",
            "callback_url": "https://signaldoctor.com",
            "webhook_secret": "https://signaldoctor.com/test-hook",
            "environment": "test",
            "updated_at": "2026-01-30T07:19:45.000000Z",
            "created_at": "2026-01-30T07:19:45.000000Z",
            "id": 3
    	}
   }


2.  Initialize A Payment
    - Request: Post
    - Endpoint: {{baseUrl}}/api/app/payment/initialize
    - Payload: app_id, email, amount, currency, reference and metadata

    SAMPLE PAYLOAD
    {
        "app_id": 3,
        "email": "omotoshomichael2503@gmail.com",
        "amount": "500000", //amount is in kobo
        "currency": "NGN",
        "reference": "KPO0BG5412698741ASD", //reference number should be unique
        "metadata": []
    }

    SAMPLE RESPONSE
    {
        "status": "success",
        "code": 201,
        "data": {
            "authorization_url": "https://checkout.paystack.com/7zhjgoe7k4x7yfj",
            "reference": "KPO0BG5412698741ASD"
        }
    }
   

3.  Redirect Callback
    - Request: Get
    - Endpoint: {{baseUrl}}/api/app/payment/callback/KPO0BG5412698741ASD
    - Payload: This is a get request and the "KPO0BG5412698741ASD" in the endpoint is the reference number from the initialize payment response

    SAMPLE RESPONSE
    if the payment has been made and transaction is successful
    {
        "status": "success",
        "code": 201,
        "data": "https://signaladoc.com/?reference=KPO0BG5412698741ASD&status=successful"
    }

    if the payment has not been made or the transaction is not successful
    {
        "status": "success",
        "code": 201,
        "data": "https://signaladoc.com/?reference=KPO0BG5412698741ASD&status=failed"
    }


4   The Webhook Listener
    - Request: Post
    - Endpoint: {{baseUrl}}/api/app/webhook/process/run (depending on the webhook provided on the app)
    - Payload: The payload is sent from Paystack
    Once the webhook url is called from Paystck, it processes the charge.success or charge.fail. Once the webhook is called, i get the signature from the request        header (x-paystack-signature) then i hashed the content of the request using hash_hmac sha512 longside with the app Paystack secret key. After hashing i compare it with the signature (x-paystack-signature) from the request header. If it's the same then i move forward to ensure idempotency else i return an error message.
For the idempotency, i get the id of the data sent, i check my wenhook events table if it exist. SO if it existed i throw an error message that webhook already existed else i move forward to check the type of event that is sent (charge.success or charge.failed). If it's charge.success i update the transaction (i get the transaction row using the reference number in that data that was sent) status (successful), paid_at (current date), channel (channel from that data sent) and raw_payload (the who data sent) else i update the transaction status (failed) and raw_payload (data sent)

Security Consideration
The security consideration used in this system are
1. validating x-paystack-signature using the HMAC SHA-512 process
2. The webhook event will reject duplicate event from Paystack
3. The payload content is hashed using the app paystack secret
4. validation of amount and reference number 
5. API rate limiter: I use the RateLimiter to make sure only 5 request can be sent per minute. I used throttle middleware through the endpoints created in the api.php file
6. I log all the gateway interactions into the gateway_response column on transaction table
