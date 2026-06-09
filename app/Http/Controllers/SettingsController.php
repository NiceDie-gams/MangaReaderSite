<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    protected SettingsService $settings;

    public function __construct(SettingsService $settings)
    {
        //$this->middleware(['auth', 'role:admin']);
        $this->settings = $settings;
    }

    public function index(): View
    {
        $settings = $this->settings->all();
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'comments_enabled' => 'sometimes|boolean',
            'reports_enabled' => 'sometimes|boolean',
            'registration_enabled' => 'sometimes|boolean',
        ]);

        $this->settings->set('comments_enabled', $request->boolean('comments_enabled'));
        $this->settings->set('reports_enabled', $request->boolean('reports_enabled'));
        $this->settings->set('registration_enabled', $request->boolean('registration_enabled'));

        return redirect()->back()->with('success', 'Настройки сайта обновлены.');
    }
}
