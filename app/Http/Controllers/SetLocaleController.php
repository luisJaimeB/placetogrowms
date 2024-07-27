<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;

class SetLocaleController extends Controller
{
    public function setLang(string $locale): JsonResponse
    {
        App::setLocale($locale);

        $messages = Lang::get('common');

        //dd($messages);

        return response()->json([
            'locale' => $locale,
            'messages' => $messages,
        ]);
    }
}
