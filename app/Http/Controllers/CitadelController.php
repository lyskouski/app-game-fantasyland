<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

class CitadelController extends Controller
{
    public function index() {
        $url = request()->query('url', '');
        return view('citadel', ['url' => $url]);
    }
}
