<?php

namespace App\Http\Controllers;

use App\Models\BannedWord;
use App\Services\BannedWordChecker;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BannedWordController extends Controller
{
    public function __construct()
    {
        //$this->middleware(['auth', 'role:admin']);
    }

    public function index(): View
    {
        $bannedWords = BannedWord::orderBy('word')->paginate(30);
        return view('admin.banned-words', compact('bannedWords'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'word' => 'required|string|max:100|unique:banned_words,word',
        ]);
        BannedWord::create(['word' => mb_strtolower($request->word)]);
        BannedWordChecker::clearCache();
        return redirect()->back()->with('success', 'Слово добавлено.');
    }

    public function destroy(BannedWord $bannedWord)
    {
        $bannedWord->delete();
        BannedWordChecker::clearCache();
        return redirect()->back()->with('success', 'Слово удалено.');
    }
}
