<?php

namespace App\Payments;

use App\Actions\CreatePaymentAction;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class PlaceToPayGateway implements PaymentMethod
{
    protected $endpoint;

    protected array $data;

    const URI = '/api/session';

    public function __construct(array $data)
    {
        $this->endpoint = config('payments.placetopay_endpoint');
        $this->data = $data;
    }

    /**
     * @throws Exception|Throwable
     */
    public function pay($data): array
    {
        $URI = self::URI;
        $createSessionEndPoint = $this->endpoint.$URI;

        $dataPayment = $this->prepareData($data);
        Log::info('Payment Data:', $dataPayment);

        $response = Http::post($createSessionEndPoint, $dataPayment);
        Log::info('Payment response:', (array) $response);

        return $this->handleResponse($response, $data, $dataPayment);
    }

    /**
     * @throws Exception
     */
    public function getInfomation($payment)
    {
        $URI = self::URI;
        $parm = $payment['request_id'];
        $createSessionEndPoint = $this->endpoint.$URI.'/'.$parm;
        $auth = [
            'auth' => $this->prepareAuth(),
        ];

        $response = Http::post($createSessionEndPoint, $auth);
        $responseData = $response->json();
        Log::info('Payment Response:', $responseData);

        if ($response->successful()) {
            return $responseData;
        } else {
            throw new Exception('Error al procesar el pago');
        }
    }

    /**
     * @throws Throwable
     */
    private function handleResponse($response, $data, $dataPayment): array
    {
        $responseBody = $response->body();
        $responseArray = json_decode($responseBody, true);

        if (isset($responseArray['status']) &&
            $responseArray['status']['status'] === 'OK' &&
            isset($responseArray['processUrl'])) {

            $lastSlashPos = strrpos($dataPayment['returnUrl'], '/');

            $lastSegment = substr($dataPayment['returnUrl'], $lastSlashPos + 1);

            $payment = CreatePaymentAction::execute($data);
            $payment->return_url = $dataPayment['returnUrl'];
            $payment->proccess_url = $responseArray['processUrl'];
            $payment->request_id = $responseArray['requestId'];
            $payment->return_id = $lastSegment;

            $payment->save();

            return ['processUrl' => $responseArray['processUrl']];

        } elseif (isset($responseArray['status']) &&
            $responseArray['status']['status'] === 'FAILED') {

            return ['status' => $responseArray['status']];
        }

        return ['error' => 'Estructura de respuesta no reconocida'];
    }

    private function prepareAuth(): array
    {
        $login = config('payments.placetopay_login');
        $secretKey = config('payments.placetopay_secret');
        $seed = date('c');
        $rawNonce = rand();

        $tranKey = base64_encode(hash('sha256', $rawNonce.$seed.$secretKey, true));
        $nonce = base64_encode($rawNonce);

        return [
            'login' => $login,
            'tranKey' => $tranKey,
            'nonce' => $nonce,
            'seed' => $seed,
        ];
    }

    private function preparePaymentBody($data): array
    {
        $prefix = 'pay-';
        $randomString = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 10));
        $reference = $prefix.$randomString;

        return [
            'reference' => $reference,
            'description' => $data['description'],
            'amount' => [
                'currency' => $data['currencyCode'],
                'total' => $data['amount'],
            ],
        ];
    }

    private function prepareBuyerData($data): array
    {
        return [
            'name' => $data['name'],
            'surname' => $data['lastName'],
            'email' => $data['email'],
            'mobile' => $data['phone'],
        ];
    }

    private function prepareData($data): array
    {
        $randomReturn = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 10));
        $data['randomReturn'] = $randomReturn;
        $expirationRange = $data['expiration'];
        $date = new DateTime;
        $date->modify('+'.$expirationRange.' minutes');
        $expiration = $date->format('c');
        $userIp = $data['userIp'];
        $userAgent = $data['userAgent'];
        $returnUrl = route('payments.return', $randomReturn);

        return [
            'auth' => $this->prepareAuth(),
            'payment' => $this->preparePaymentBody($data),
            'buyer' => $this->prepareBuyerData($data),
            'expiration' => $expiration,
            'returnUrl' => $returnUrl,
            'ipAddress' => $userIp,
            'userAgent' => $userAgent,
        ];
    }
}
