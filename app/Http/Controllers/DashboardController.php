<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Invoice;
use App\Models\Microsite;
use App\Models\Payment;
use App\Models\Suscription;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Microsite::query();

        if ($request->has('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%")->get();
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $microsites = $query->get();
        $categories = Category::all();

        return Inertia::render('Welcome', [
            'microsites' => $microsites,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category_id']),
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }

    public function dashboard(): Response
    {
        $user = auth()->user();
        $microsites = Microsite::where('user_id', $user->id)
            ->with(['currencies'])
            ->get()
            ->keyBy('type_site_id');

        $invoices = null;
        $suscriptions = null;
        $payments = null;
        $typeSiteIdFlag = null;
        $message = null;

        if ($microsites->has(1)) {
            $payments = Payment::where('microsite_id', $microsites->get(1)->id)->get();
            $typeSiteIdFlag = 1;
        } elseif ($microsites->has(2)) {
            $invoices = Invoice::where('microsite_id', $microsites->get(2)->id)->get();
            $typeSiteIdFlag = 2;
        } elseif ($microsites->has(3)) {
            $suscriptions = Suscription::where('microsite_id', $microsites->get(3)->id)
                ->with(['microsite', 'initialPayment', 'user', 'suscriptionPlan'])
                ->get();
            $typeSiteIdFlag = 3;
        } else {
            $typeSiteIdFlag = 0;
            $message = 'debes crear un micrositio primero';
        }

        return inertia('Dashboard', [
            'invoices' => $invoices,
            'suscriptions' => $suscriptions,
            'payments' => $payments,
            'typeSiteIdFlag' => $typeSiteIdFlag,
            'message' => $message,
        ]);
    }
}
