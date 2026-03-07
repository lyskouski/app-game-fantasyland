<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

use App\Providers\AppProxyProvider;

abstract class Controller
{
    protected AppProxyProvider $curl;

    public string $url = 'https://www.fantasyland.ru/';

    public function __construct()
    {
        $this->curl = new AppProxyProvider();
    }

    public function get(string $url, ?array $post = null) {
        $data = $this->curl->boot($this->url . $url, $post);
        $data = str_replace('src="../', 'src="' . $this->url, $data);
        $data = str_replace('SRC="../', 'src="' . $this->url, $data);
        $data = str_replace('src=../', 'src=' . $this->url, $data);
        $data = str_replace('src="/', 'src="' . $this->url, $data);
        $data = str_replace('src=/', 'src=' . $this->url, $data);
        $data = str_replace("BACKGROUND='../", "background='" . $this->url, $data);
        $data = str_replace('BACKGROUND="../', 'background="' . $this->url, $data);
        $data = str_replace('background="../', 'background="' . $this->url, $data);
        return view('generic', ['data' => $data]);
    }

    public function captcha(?string $t) {
        $t = $t ?? random_int(0, 1000000);
        $html = $this->curl->boot($this->url . 'cgi/png.php?c=' . $t, null, false);
        return 'data:image/png;base64,' . base64_encode($html);
    }
}
