<?php

// namespace App\Http\Controllers\Auth;


namespace App\Http\Controllers\Auth;

use App\Enums\RolesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

// use App\Enums\RolesEnum;
// use App\Http\Controllers\Controller;
// use App\Http\Requests\Auth\LoginRequest;
// use Illuminate\Http\RedirectResponse;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Route;
// use Inertia\Inertia;
// use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('auth/login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => $request->session()->get('status'),
        ]);
    }

    // /**
    //  * Handle an incoming authentication request.
    //  */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // $user = Auth::user();

        // // Redirect admins and vendors to Filament
        // if ($user->hasAnyRole([RolesEnum::Admin, RolesEnum::Vendor])) {
        //     return redirect()->intended(route('filament.admin'));
        // };

        // // Redirect normal users to dashboard
        // return redirect()->intended(route('dashboard'));






        // $user = Auth::user();

        // if ($user->hasAnyRole([RolesEnum::Admin, RolesEnum::Vendor])) {
        //     return Inertia::location(route('filament.admin.dashboard')); // Use correct named route
        // } else {
        //     return Inertia::location(route('dashboard', absolute: false)); // Ensure return
        // }

        $user = Auth::user();
        if ($user->hasAnyRole([RolesEnum::Admin, RolesEnum::Vendor])) {
            return Inertia::location(route('dashboard', absolute: false));
            // return Inertia::location(route('/admin'));
            return Inertia::location(route('filament.admin.dashboard'));
        } else {
            $route = route('dashboard', absolute: false);
            return Inertia::location($route);
            // return redirect()->intended($route);
            // return redirect()->intended(route('dashboard', absolute: false));
        }
    }
    /**
     * Handle an incoming authentication request.
     */
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     $user = Auth::user();

    //     // Redirect admins and vendors to Filament
    //     if ($user->hasAnyRole([RolesEnum::Admin, RolesEnum::Vendor])) {
    //         return redirect()->intended(route('filament.admin.pages.dashboard'));
    //         // return redirect()->intended(route('filament.admin'));
    //     }

    //     // Redirect normal users to dashboard
    //     return redirect()->intended(route('dashboard'));
    // }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
