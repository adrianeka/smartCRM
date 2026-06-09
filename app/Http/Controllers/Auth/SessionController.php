<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{
    public function logoutOtherDevices(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = auth()->user();

        Auth::logoutOtherDevices($request->password);

        // Update the session's password hashes to match the new database hash.
        // This prevents the AuthenticateSession middleware from logging out the current user.
        $passwordHash = $user->getAuthPassword();
        $guard = Auth::guard('web');
        if (method_exists($guard, 'hashPasswordForCookie')) {
            $passwordHash = $guard->hashPasswordForCookie($passwordHash);
        }

        $sessionData = [
            'password_hash_web' => $passwordHash,
        ];

        if (method_exists($guard, 'getName')) {
            $sessionData['password_hash_' . $guard->getName()] = $user->getAuthPassword();
        }

        $request->session()->put($sessionData);

        // Delete other sessions from the database
        DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('id', '!=', session()->getId())
            ->delete();

        return back()->with('status', 'Berhasil mengeluarkan semua perangkat lain.');
    }
}
