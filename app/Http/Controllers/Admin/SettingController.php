<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting; // ou le modèle où tu stockes la config

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->is_admin) {
                abort(403, 'Accès interdit. Vous devez être administrateur.');
            }
            return $next($request);
        });
    }

    public function edit()
    {
        // récupère la durée d'essai depuis la base ou valeur par défaut
        $trialDays = Setting::get('trial_days') ?? 10; // 10 jours par défaut

        return view('admin.settings.edit', compact('trialDays'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'trial_days' => 'required|integer|min:1|max:365',
        ]);

        Setting::set('trial_days', $request->trial_days);

        return redirect()->back()->with('success', 'Durée de l’essai mise à jour');
    }
}
