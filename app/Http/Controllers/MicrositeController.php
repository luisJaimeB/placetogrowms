<?php

namespace App\Http\Controllers;

use App\Actions\CreateMicrositeAction;
use App\Actions\UpdateMicrositeAction;
use App\Http\Requests\MicrositeRequest;
use App\Http\Requests\MicrositeUpdateRequest;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Microsite;
use App\Models\TypeSite;

class MicrositeController extends Controller
{
    public function index()
    {
        $microsites = Microsite::with(['typeSite', 'category'])->get();

        return inertia('Microsites/Index', ['microsites' => $microsites]);
    }

    public function create()
    {
        $sites_type = TypeSite::all();
        $categories = Category::all();
        $currencies = Currency::all();

        return Inertia('Microsites/Create', [
            'sites_type' => $sites_type,
            'categories' => $categories,
            'currencies' => $currencies
        ]);
    }

    public function store(MicrositeRequest $request)
    {
        $result = CreateMicrositeAction::execute($request->validated());

        if (!$result) {
            return back()->with('error', 'Ha ocurrido un error');
        }

        return redirect()->route('microsites.show', $result);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $microsite = Microsite::with(['typeSite', 'category', 'currencies'])->findOrFail($id);
        return inertia('Microsites/Show', ['microsite' => $microsite]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $microsite = Microsite::with(['typeSite', 'category', 'currencies'])->findOrFail($id);
        $categories = Category::all();
        $types = TypeSite::all();
        $currencies = Currency::all();

        return inertia('Microsites/Edit', [
            'microsite' => $microsite,
            'categories' => $categories,
            'types' => $types,
            'currencies' => $currencies
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MicrositeUpdateRequest $request, Microsite $microsite)
    {

        UpdateMicrositeAction::execute($request->validated(), $microsite);
        return redirect()->route('microsites.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Microsite $microsite)
    {
        $microsite->delete();

        return redirect()->route('microsites.index');
    }
}
