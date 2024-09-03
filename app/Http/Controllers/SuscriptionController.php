<?php

namespace App\Http\Controllers;

use App\Actions\CreateSuscriptionPlanAction;
use App\Constants\Periodicities;
use App\Http\Requests\CreateSuscriptionRequest;
use App\Models\Microsite;
use App\Models\SuscriptionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class SuscriptionController extends Controller
{
    public function index(): Response
    {
        $plans = SuscriptionPlan::all();

        return inertia('SuscriptionPlanes/Index', ['plans' => $plans]);
    }

    public function create(): Response
    {
        $user = auth()->user();

        $microsites = Microsite::with(['typeSite', 'category'])
            ->where('user_id', $user->id)
            ->get();

        $periodicities = Periodicities::toArray();

        return Inertia('SuscriptionPlanes/Create', [
            'periodicities' => $periodicities,
            'microsites' => $microsites,
        ]);
    }

    public function store(CreateSuscriptionRequest $request): RedirectResponse
    {
        CreateSuscriptionPlanAction::execute($request->validated());

        return redirect()->route('planes.index');
    }

    public function edit(SuscriptionPlan $plan): Response
    {
        $user = auth()->user();

        $microsites = Microsite::with(['typeSite', 'category'])
            ->where('user_id', $user->id)
            ->get();

        $periodicities = Periodicities::toArray();

        return inertia('SuscriptionPlanes/Edit', [
            'plan' => $plan,
            'periodicities' => $periodicities,
            'microsites' => $microsites,
            ]);
    }

    public function update(CreateSuscriptionRequest $request, SuscriptionPlan $plan): RedirectResponse
    {
        $plan->update($request->validated());

        return redirect()->route('planes.index');
    }

    public function destroy(SuscriptionPlan $plan): RedirectResponse
    {
        $plan->delete();

        return redirect()->route('planes.index');
    }
}
