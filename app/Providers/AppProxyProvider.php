<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Providers;

class AppProxyProvider
{
    protected $browser = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3';
    protected $fcurl;

    public function __construct()
    {
        $this->fcurl = storage_path('cookie.txt');
        $dir = dirname($this->fcurl);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        if (!file_exists($this->fcurl)) {
            touch($this->fcurl);
        }
    }

    protected function convertEncoding($string, $from, $to)
    {
        if (function_exists('iconv')) {
            return iconv($from, $to, $string);
        } elseif (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($string, $to, $from);
        } else {
            return $string;
        }
    }

    protected function convert(array $data): string
    {
        $converted = [];
        foreach ($data as $key => $value) {
            $converted[$key] = $this->convertEncoding($value, 'UTF-8', 'cp1251');
        }
        return http_build_query($converted);
    }

    public function boot(string $url, ?array $get = null, ?array $post = null, bool $convert = true): string
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($curl, CURLOPT_USERAGENT, $this->browser);
        if ($get) {
            $url .= '?' . $this->convert($get);
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        if ($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->convert($post));
        }
        curl_setopt($curl, CURLOPT_COOKIEFILE, $this->fcurl);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $this->fcurl);
        $result = curl_exec($curl);
        return $convert ? $this->convertEncoding($result, 'cp1251', 'UTF-8') : $result;
    }
}
