<?php

namespace App\Http\Controllers;

use App\Actions\CreateMicrositeAction;
use App\Actions\UpdateMicrositeAction;
use App\Constants\TypesSites;
use App\Http\Requests\MicrositeRequest;
use App\Models\Category;
use App\Models\Microsite;
use App\Models\TypeSite;
use Illuminate\Http\Request;

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
        return Inertia('Microsites/Create', [
            'sites_type' => $sites_type,
            'categories' => $categories,
        ]);
    }

    public function store(MicrositeRequest $request)
    {
        $createAction = new CreateMicrositeAction($request->validated());
        $createAction->execute();

        return redirect()->route('microsites.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $microsite = Microsite::with(['typeSite', 'category'])->findOrFail($id);
        //dd($microsite);
        return inertia('Microsites/Show', ['microsite' => $microsite]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $microsite = Microsite::with(['typeSite', 'category'])->findOrFail($id);
        $categories = Category::all();
        $types = TypeSite::all();

        return inertia('Microsites/Edit', [
            'microsite' => $microsite,
            'categories' => $categories,
            'types' => $types,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MicrositeRequest $request, Microsite $microsite)
    {
        $updateMicrositeAction = new UpdateMicrositeAction($request->validated(), $microsite);
        $microsite = $updateMicrositeAction->execute();
        return redirect()->route('microsites.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
