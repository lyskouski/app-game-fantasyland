<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Providers\AppContentWrapper;
use App\Providers\AppProxyProvider;

abstract class Controller
{
    protected AppProxyProvider $curl;

    public function __construct()
    {
        $this->curl = new AppProxyProvider();
    }

    public function get(string $url, ?array $post = null) {
        $data = $this->curl->boot('https://www.fantasyland.ru/' . $url, $post);
        $wrapper = new AppContentWrapper($data);
        return view('generic', ['data' => $wrapper->get()]);
    }

    public function captcha(?string $t) {
        $t = $t ?? random_int(0, 1000000);
        $html = $this->curl->boot('https://www.fantasyland.ru/cgi/png.php?c='. $t, null, false);
        return 'data:image/png;base64,' . base64_encode($html);
    }
}
