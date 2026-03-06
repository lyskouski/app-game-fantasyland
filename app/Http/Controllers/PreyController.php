<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use Native\Mobile\Facades\Dialog;

class PreyController extends MainController
{
    public function stop() {
        $data = request()->all();
        $html = $this->curl->boot('https://www.fantasyland.ru/cgi/work_stop.php?' . http_build_query($data));
        preg_match("/Syst\(\s*'([^']*)'/u", $html, $matches);
        $message = isset($matches[1]) ? strip_tags($matches[1]) : null;
        if ($message) {
            Dialog::toast($message);
        }
        return view('prey_stop', [...$this->onPlace($html), ...$this->onPrey($html)]);
    }

    public function run() {
        $post = request()->post();
        $html = $this->curl->boot('https://www.fantasyland.ru/cgi/work_start.php', $post);
        return view('prey_start', [...$this->onPlace($html), ...$this->onPrey($html)]);
    }

    public function start() {
        $html = $this->curl->boot('https://www.fantasyland.ru/cgi/work_start.php');
        return view('prey_start', [...$this->onPlace($html), ...$this->onPrey($html)]);
    }
}