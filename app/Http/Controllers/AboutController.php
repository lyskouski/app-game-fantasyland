<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Services\AboutParser;

class AboutController extends Controller
{
    protected AboutParser $parser;

    public function __construct() {
        parent::__construct();
        $this->parser = new AboutParser;
    }

    public function item() {
        $html = $this->get('/cgi/item_desc.php');
        return view('about_item', $this->parser->item($html));
    }

    public function army() {
        $html = $this->get('/cgi/army_desc.php');
        $id = request()->input('id', '0');
        return view('about_army', $this->parser->army($html, $id));
    }
}
