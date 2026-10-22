<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Services\AgeAssurance;

final class AgeController extends Controller
{
    public function index()
    {
        if (AgeAssurance::isDeclared()) {
            return redirect('/');
        }
        return view('age', ['years' => range(now()->year, 1900)]);
    }

    public function declare()
    {
        if (AgeAssurance::isDeclared()) {
            return redirect('/');
        }
        $data = request()->validate([
            'birth_year' => ['required', 'integer', 'between:1900,' . now()->year],
        ]);
        AgeAssurance::declare((int) $data['birth_year']);
        return redirect('/');
    }

    public function parental()
    {
        return view('parental', [
            'hasPin' => AgeAssurance::hasPin(),
            'locked' => AgeAssurance::isSocialLocked(),
        ]);
    }

    public function parentalSave()
    {
        $data = request()->validate([
            'pin' => ['nullable', 'string'],
            'new_pin' => ['nullable', 'digits:4', 'confirmed'],
            'social_lock' => ['nullable'],
            'remove_pin' => ['nullable'],
        ]);
        if (AgeAssurance::hasPin() && !AgeAssurance::checkPin((string) ($data['pin'] ?? ''))) {
            return back()->withErrors(['pin' => 'Неверный PIN']);
        }
        if (!empty($data['remove_pin'])) {
            AgeAssurance::removePin();
            AgeAssurance::setSocialLock(false);
            return redirect('/parental')->with('status', 'PIN удалён, ограничения сняты');
        }
        if (!empty($data['new_pin'])) {
            AgeAssurance::setPin($data['new_pin']);
        }
        $lock = !empty($data['social_lock']);
        if ($lock && !AgeAssurance::hasPin()) {
            return back()->withErrors(['new_pin' => 'Для блокировки задайте PIN']);
        }
        AgeAssurance::setSocialLock($lock);
        return redirect('/parental')->with('status', 'Настройки сохранены');
    }
}
