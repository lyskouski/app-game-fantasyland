<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

class PreyController extends MainController
{
    public function stop() {
        $html = $this->curl->boot('https://www.fantasyland.ru/cgi/work_stop.php');
        return view('prey_stop', [...$this->onPlace($html), ...$this->onPrey($html)]);
    }
}