<?php

namespace App\Http\Controllers\Suscriptor;

use App\Constants\Roles;
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
        $user = Auth::user();
        $isEditing = false;

        if ($user->hasRole(Roles::ADMIN->value)) {
            $suscriptions = Suscription::with(['suscriptionPlan', 'microsite', 'initialPayment'])->get();
            $isEditing = true;
        } else {
            $suscriptions = Suscription::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereHas('microsite', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    });
            })->with(['suscriptionPlan', 'microsite', 'initialPayment'])->get();

            $isOwner = $suscriptions->contains(function ($suscription) use ($user) {
                return $suscription->microsite->owner_id === $user->id;
            });

            if ($isOwner) {
                $isEditing = true;
            }
        }

        return inertia('Suscriptions/Index', [
            'suscriptions' => $suscriptions,
            'editing' => $isEditing,
        ]);
    }

    public function show($id): Response|RedirectResponse
    {
        $suscription = Suscription::with(['suscriptionPlan', 'microsite', 'initialPayment'])->findOrFail($id);

        $payments = Payment::where('suscription_id', $suscription->id)->with(['currency'])->get();

        return inertia('Suscriptions/Show', [
            'suscription' => $suscription,
            'payments' => $payments,
            'flash' => ['message' => 'No tienes permiso para ver los pagos de esta suscripción.'],
        ]);
    }

    public function destroy($id): RedirectResponse|JsonResponse
    {
        $suscription = Suscription::where('id', $id)
            ->with(['suscriptionPlan', 'microsite', 'initialPayment'])
            ->first();
        $tokenization = $suscription->initialPayment;
        $paymentMethod = $tokenization->payment_method;
        $paymentArray = $tokenization->toArray();
        $paymentArray['token'] = $suscription->token;
        Log::info('Request Invalidate Token:', $paymentArray);

        try {
            $gateway = PaymentFactory::create($paymentMethod, $paymentArray);
            $paymentService = new PaymentGatewayService($gateway, $paymentArray);
            $tokenCancelation = $paymentService->cancelToken();
            $suscription->status = SuscriptionsStatus::CANCELLED->value;
            $suscription->save();

            return redirect()->route('subscriptions.index', ['tokenCancelation' => $tokenCancelation]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
