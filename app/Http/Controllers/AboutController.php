<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Services\AboutParser;

class AboutController extends Controller
{
    public function item() {
        $html = $this->get('/cgi/item_desc.php');
        return view('about_item', (new AboutParser)->item($html));
    }
}
