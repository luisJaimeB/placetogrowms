<?php

namespace App\Http\Controllers;

use App\Actions\CreateMicrositeAction;
use App\Actions\UpdateMicrositeAction;
use App\Constants\Roles;
use App\Http\Requests\MicrositeRequest;
use App\Http\Requests\MicrositeUpdateRequest;
use App\Models\BuyerIdType;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Microsite;
use App\Models\OptionalField;
use App\Models\Payment;
use App\Models\SuscriptionPlan;
use App\Models\TypeSite;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use Throwable;

class MicrositeController extends Controller
{
    use AuthorizesRequests;

    public function index(): Response
    {
        $user = auth()->user();

        if ($user->hasRole(Roles::ADMIN)) {
            $microsites = Microsite::with(['typeSite', 'category'])->get();
        } else {
            $microsites = Microsite::with(['typeSite', 'category'])
                ->where('user_id', $user->id)
                ->get();
        }

        return inertia('Microsites/Index', ['microsites' => $microsites]);
    }

    public function create(): Response
    {
        $sites_type = TypeSite::all();
        $categories = Category::all();
        $currencies = Currency::all();
        $buyer_id_types = BuyerIdType::all();
        $optionals = OptionalField::all();
        $plans = SuscriptionPlan::where('user_id', Auth::user());

        return Inertia('Microsites/Create', [
            'sites_type' => $sites_type,
            'categories' => $categories,
            'currencies' => $currencies,
            'buyer_id_types' => $buyer_id_types,
            'optionals' => $optionals,
            'plans' => $plans,
        ]);
    }

    public function store(MicrositeRequest $request): redirectResponse
    {
        try {
            $data = $request->validated();
            $microsite = CreateMicrositeAction::execute($data);
            //$this->authorize('createMicrosites', $microsite);

            if (! $microsite) {
                return back()->with('error', 'Microsite could not be created.')->withInput();
            } elseif ($microsite->type_site_id === '3') {
                return redirect()->route('planes.create');
            } else {
                return redirect()->route('microsites.show', $microsite->id);
            }
        } catch (Throwable $e) {
            return back()->withErrors([
                'error' => 'Microsite could not be created: '.$e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ])->withInput();
        }
    }

    public function show($id): Response | RedirectResponse
    {
        $microsite = Microsite::with(['typeSite', 'category', 'currencies'])->findOrFail($id);

        /**try {
            $this->authorize('viewPayments', $microsite);
        } catch (AuthorizationException $e) {

            return redirect()->back()->with('error', 'No tienes permiso para ver los pagos de este micrositio.');
        }**/

        $payments = Payment::where('microsite_id', $id)->with(['currency'])->get();

        return inertia('Microsites/Show', [
            'microsite' => $microsite,
            'payments' => $payments,
            'flash' => ['message' => 'No tienes permiso para ver los pagos de este micrositio.']
        ]);
    }

    public function edit($id): Response
    {
        $microsite = Microsite::with(['typeSite', 'category', 'currencies'])->findOrFail($id);
        $categories = Category::all();
        $types = TypeSite::all();
        $currencies = Currency::all();

        return inertia('Microsites/Edit', [
            'microsite' => $microsite,
            'categories' => $categories,
            'types' => $types,
            'currencies' => $currencies,
        ]);
    }

    public function update(MicrositeUpdateRequest $request, Microsite $microsite): redirectResponse
    {
        UpdateMicrositeAction::execute($request->validated(), $microsite);

        return redirect()->route('microsites.index');
    }

    public function destroy(Microsite $microsite): redirectResponse
    {
        $microsite->delete();

        return redirect()->route('microsites.index');
    }
}
