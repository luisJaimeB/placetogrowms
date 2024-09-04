<?php

namespace App\Http\Controllers\Suscriptor;

use App\Constants\SuscriptionsStatus;
use App\Factories\PaymentFactory;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Suscription;
use App\Services\PaymentGatewayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Response;

class SuscriptionController extends Controller
{
    public function index(): Response
    {
        $suscriptions = Suscription::where('user_id', Auth::id())
            ->with(['suscriptionPlan', 'microsite', 'payment'])
            ->get();

        return inertia('Suscriptions/Index', ['suscriptions' => $suscriptions]);
    }

    public function destroy($id): RedirectResponse|JsonResponse
    {
        $suscription = Suscription::where('id', $id)
            ->with(['suscriptionPlan', 'microsite', 'payment'])
            ->first();
        $tokenization = $suscription->payment;
        $paymentMethod = $tokenization->payment_method;
        $paymentArray = $tokenization->toArray();
        $paymentArray['token'] = $suscription->token;
        Log::info('Request Invalidate Token:', $paymentArray);

        try {
            $gateway = PaymentFactory::create($paymentMethod, $paymentArray);
            $paymentService = new PaymentGatewayService($gateway, $paymentArray);
            $tokenCancelation = $paymentService->cancelToken();
            $suscription->status = SuscriptionsStatus::canceled;
            $suscription->save();

            return redirect()->route('subscriptions.index', ['tokenCancelation' => $tokenCancelation]);
        }catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
