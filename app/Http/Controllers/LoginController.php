<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login() {
        $data = request()->only(['login', 'password']);
        $curl = new \App\Providers\AppProxyProvider();
        $loginResult = $curl->boot('https://www.fantasyland.ru/login.php', $data);
        if (preg_match("#<FONT COLOR='\\#FF0000'>(.*?)</FONT>#is", $loginResult, $matches)) {
            $match = $matches[1];
            return view('login', ['error' => $match]);
        }
        $curl->boot('https://www.fantasyland.ru/ch/chch.php');
        return view('main');
    }
}
