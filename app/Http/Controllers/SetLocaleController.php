<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class SetLocaleController extends Controller
{
    public function setLang(string $locale): RedirectResponse
    {
        if (! in_array($locale, ['en', 'es'])) {
            abort(400, 'Invalid locale');
        }

        session(['locale' => $locale]);
        //dd(session('locale'));

        return redirect()->back();
    }
}
