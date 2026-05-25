<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        if (! str_ends_with($googleUser->email, '@muci.org')) {
            return redirect('/tracker')->with('error', 'Acceso restringido a cuentas @muci.org.');
        }

        $user = User::updateOrCreate(
            ['email' => $googleUser->email],
            [
                'name'      => $googleUser->name,
                'google_id' => $googleUser->id,
                'avatar'    => $googleUser->avatar,
            ]
        );

        Auth::login($user, remember: true);

        return redirect()->intended('/tracker');
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/tracker');
    }
}
