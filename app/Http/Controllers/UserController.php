<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    public function show(User $user): View
    {
        abort_unless(auth()->id() === $user->id, 403);
        $user->load('favoriteTitles.tags');

        return view('user-show', compact('user'));
    }
}
