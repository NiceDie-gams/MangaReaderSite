<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(User $user): View
    {
        abort_unless(auth()->id() === $user->id, 403);
        $user->load('favoriteTitles.tags');

        return view('user-show', compact('user'));
    }

    public function updateUserInfo(User $user, Request $request)
    {
        abort_unless(auth()->id() === $user->id, 403);

        $user->update([
            'name' => $request->validate(['name' => 'required|string|max:255'])['name'],
        ]);

        return redirect()->route('users.show', $user);
    }
}
