<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(): \Illuminate\Contracts\Foundation\Application|Factory|View|Application|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('pages.home');
        } else {
            return view('auth.login');
        }
    }

    public function login(Request $request): JsonResponse|RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $request->session()->regenerate(true);
            $request->session()->put('user_token', $user->token->token);
//            $request->session()->put('expires_at', now()->addDays(30));
            return redirect()->route('home');
        }
        else{
            return redirect()->route('login')->withErrors(['wrong_info' => 'Incorrect Credential']);
        }
    }

    public function destroy(): RedirectResponse
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
