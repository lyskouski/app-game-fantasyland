<?php

namespace App\Http\Controllers;

use App\Providers\AppContentWrapper;
use App\Providers\AppProxyProvider;

abstract class Controller
{
    public function get(string $url, ?array $post = null) {
        $curl = new AppProxyProvider();
        $data = $curl->boot('https://www.fantasyland.ru/' . $url, $post);
        $wrapper = new AppContentWrapper($data);
        return view('generic', ['data' => $wrapper->get()]);
    }
}
