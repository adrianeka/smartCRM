<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WaitingAssignmentController extends Controller
{
    public function show(Request $request)
    {
        // If the user already has a role, they don't need to wait.
        if ($request->user() && $request->user()->roles()->exists()) {
            return redirect()->route('filament.admin.pages.dashboard');
        }

        return view('auth.waiting-assignment');
    }
}
