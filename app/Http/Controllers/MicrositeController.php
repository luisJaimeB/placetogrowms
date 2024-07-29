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
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Throwable;

class MicrositeController extends Controller
{
    public function index(): Response
    {
        $microsites = Microsite::with(['typeSite', 'category'])
            ->get();

        return inertia('Microsites/Index', ['microsites' => $microsites]);
    }

    public function create(): Response
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

    public function store(MicrositeRequest $request): redirectResponse
    {
        try {
            $data = $request->validated();
            $microsite = CreateMicrositeAction::execute($data);

            if (!$microsite) {
                return back()->with('error', 'Microsite could not be created.')->withInput();
            }

            return redirect()->route('microsites.show', $microsite->id);
        } catch (Throwable $e) {
            return back()->withErrors([
                'error' => 'Microsite could not be created: ' . $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ])->withInput();
        }
    }

    public function show($id): Response
    {
        $microsite = Microsite::with(['typeSite', 'category', 'currencies'])->findOrFail($id);
        return inertia('Microsites/Show', ['microsite' => $microsite]);
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
            'currencies' => $currencies
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
