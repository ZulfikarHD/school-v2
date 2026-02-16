<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $request->user(),
            ],
            'activeRole' => $this->resolveActiveRole($request),
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }

    /**
     * Resolve the active role for the authenticated user.
     *
     * Akan menggunakan spatie/laravel-permission ketika sudah di-wire.
     * Saat ini default ke null (frontend fallback ke AdminLayout).
     */
    private function resolveActiveRole(Request $request): ?string
    {
        $user = $request->user();

        if (! $user) {
            return null;
        }

        // TODO: Gunakan spatie/laravel-permission ketika sudah di-wire ke User model
        // return $user->roles->first()?->name;
        return null;
    }
}
