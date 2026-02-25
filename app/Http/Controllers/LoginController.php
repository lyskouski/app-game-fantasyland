<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request) {
        $data = $request->post();
        $curl = new \App\Providers\AppProxyProvider();
        $strem = '=== LOGIN ===';
        $strem .= $curl->boot('https://www.fantasyland.ru/login.php', $data);
        $strem .= '=== CHCH ===';
        $strem .= $curl->boot('https://www.fantasyland.ru/ch/chch.php');
        return view('main', ['strem' => $strem]);
    }
}
