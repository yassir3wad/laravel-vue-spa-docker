<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\UserProfileResource;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function me()
    {
        return new UserProfileResource(Auth::user());
    }
}
