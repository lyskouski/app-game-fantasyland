<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

class GenericController extends Controller
{
    public function index($url) {
        $data = request()->all();
        return $this->get($url . '?' . http_build_query($data));
    }

    public function indexPost($url) {
        $data = request()->input();
        $post = request()->post();
        return $this->get($url . '?' . http_build_query($data), $post);
    }
}
