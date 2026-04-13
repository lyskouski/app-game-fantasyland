<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\CraftParser;
use App\Services\LocationParser;
use App\Services\PreyParser;

class PreyController extends MainController
{
    public function stop() {
        $data = request()->all();
        $html = $this->get('cgi/work_stop.php', $data);
        Notification::addIfExists($html);
        $loc = new LocationParser();
        if (strpos($html, 'craft_favorite_ref.php') !== false) {
            return view('craft_stop', [
                ...$loc->onPlace($html),
                ...(new CraftParser)->parse($html),
                'captcha' => $this->captcha(time())
            ]);
        } else {
            return view('prey_stop', [
                ...$loc->onPlace($html),
                ...(new PreyParser)->parse($html, $this->captcha(time()))
            ]);
        }
    }

    public function run() {
        $html = $this->post('cgi/work_start.php', []);
        Notification::addIfExists($html);
        return view('prey_start', [
            ...(new LocationParser)->onPlace($html),
            ...(new PreyParser)->parse($html, $this->captcha(time()))
        ]);
    }

    public function start() {
        $html = $this->get('cgi/work_start.php', []);
        Notification::addIfExists($html);
        return view('prey_start', [
            ...(new LocationParser)->onPlace($html),
            ...(new PreyParser)->parse($html, $this->captcha(time()))
        ]);
    }

    public function favorite() {
        $data = request()->all();
        $this->get('cgi/craft_favorite_ref.php', $data);
        $html = $this->get('cgi/no_combat.php', []);
        return view('craft_stop', [
            ...(new LocationParser)->onPlace($html),
            ...(new CraftParser)->parse($html),
            'captcha' => $this->captcha(time())
        ]);
    }
}