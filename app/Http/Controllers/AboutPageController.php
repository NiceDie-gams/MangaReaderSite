<?php

namespace App\Http\Controllers;
use App\Models\Setting; 

use Illuminate\Http\Request;

class AboutPageController extends Controller
{
    public function about()
    {
        // Получаем текст для ключа 'about_text', если нет - дефолтное значение
        $aboutText = Setting::where('key', 'about_text')->value('value') ?? 'Текст о компании пока не заполнен.';

        return view('about', compact('aboutText'));
    }


    public function updateAbout(Request $request)
    {
        $request->validate(['about_text' => 'required|string']);
        Setting::updateOrCreate(['key' => 'about_text'], ['value' => $request->about_text]);
        return back()->with('success', 'Текст обновлён');
    }
}
