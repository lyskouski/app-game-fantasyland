<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Providers;

class AppProxyProvider
{
    protected $browser = 'Mozilla/5.0 (X11; Linux x86_64; rv:149.0) Gecko/20100101 Firefox/149.0';
    protected $fcurl;

    public function __construct(string $type = 'cookie')
    {
        $this->fcurl = storage_path($type . '.txt');
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

    protected function convert(array $data, bool $convert = true): string
    {
        $converted = [];
        foreach ($data as $key => $value) {
            $converted[$key] = $convert ? $this->convertEncoding($value, 'UTF-8', 'cp1251') : $value;
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
        curl_setopt($curl, CURLOPT_MAXREDIRS, 5);

        $parsedUrl = parse_url($url);
        $baseUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
        $headers = [
        //    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        //    'Accept-Encoding: gzip, deflate',
            'Accept-Language: ru-RU,ru;q=0.9',
            'Cache-Control: no-cache',
            'Pragma: no-cache',
            'Upgrade-Insecure-Requests: 1',
            'Origin: ' . $baseUrl,
            'Referer: ' . $baseUrl . '/',
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_USERAGENT, $this->browser);
        if ($get) {
            $url .= '?' . $this->convert($get, $convert);
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        if ($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->convert($post, $convert));
        }
        curl_setopt($curl, CURLOPT_COOKIEFILE, $this->fcurl);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $this->fcurl);
        $result = curl_exec($curl);
        return $convert ? $this->convertEncoding($result, 'cp1251', 'UTF-8') : $result;
    }
}
