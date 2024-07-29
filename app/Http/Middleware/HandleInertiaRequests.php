<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'user.roles' => $request->user() ? $request->user()->roles->pluck('name') : [],
            'user.permissions' => $request->user() ? $request->user()->getPermissionsViaRoles()->pluck('name') : [],
            'trans' => $this->getTranslations(),
            'locale' => session('locale', config('app.locale')),
        ];
    }

    private function getTranslations(): array
    {
        $locale = app()->getLocale();
        $files = ['common']; // Agrega más archivos de traducción si es necesario

        $translations = [];
        foreach ($files as $file) {
            $translations[$file] = trans($file, [], $locale);
        }

        return $translations;
    }
}
