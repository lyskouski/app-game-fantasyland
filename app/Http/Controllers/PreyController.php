<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

class PreyController extends MainController
{
    public function stop() {
        $data = request()->all();
        $html = $this->curl->boot($this->url . 'cgi/work_stop.php?' . http_build_query($data));
        if (strpos($html, 'craft_favorite_ref.php') !== false) {
            return view('craft_stop', [...$this->onPlace($html), ...$this->onCraft($html)]);
        } else {
            return view('prey_stop', [...$this->onPlace($html), ...$this->onPrey($html)]);
        }
    }

    public function run() {
        $post = request()->post();
        $html = $this->curl->boot($this->url . 'cgi/work_start.php', $post);
        return view('prey_start', [...$this->onPlace($html), ...$this->onPrey($html)]);
    }

    public function start() {
        $html = $this->curl->boot($this->url . 'cgi/work_start.php');
        return view('prey_start', [...$this->onPlace($html), ...$this->onPrey($html)]);
    }

    public function favorite() {
        $data = request()->all();
        $this->curl->boot($this->url . 'cgi/craft_favorite_ref.php?' . http_build_query($data));
        $html = $this->curl->boot($this->url . 'cgi/no_combat.php');
        return view('craft_stop', [...$this->onPlace($html), ...$this->onCraft($html)]);
    }
}