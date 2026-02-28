<?php

namespace App\Providers;

class AppProxyProvider
{
    protected $browser = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3';
    protected $fcurl;

    public function __construct()
    {
        $this->fcurl = storage_path('app/cookie.txt');
        if (!file_exists($this->fcurl)) {
            touch($this->fcurl);
        }
    }

    public function boot(string $url, ?array $post = null): string
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($curl, CURLOPT_USERAGENT, $this->browser);
        $loc = iconv('UTF-8', 'cp1251', $url);
        curl_setopt($curl, CURLOPT_URL, $loc);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        if ($post) {
            $data = iconv('UTF-8', 'cp1251', http_build_query($post));
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_COOKIEFILE, $this->fcurl);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $this->fcurl);
        return curl_exec($curl);
    }
}
