<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $users = User::all();
        $usersArray = [];
        foreach ($users as $user) {
            $usersArray[$user->token->token] = $user->name;
        }
        return view('pages.home', compact('usersArray'));
    }
}
