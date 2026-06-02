<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function indexV2()
    {
        return view('settings.index', ['user' => Auth::user()]);
    }
}
