<?php

namespace Trt;

class Api
{
    protected $key;     // API key
    protected $secret;  // API secret
    protected $url;     // API base URL
    protected $version; // API version
    protected $curl;    // curl handle

    function __construct($key, $secret, $url='https://api.therocktrading.com', $version='1', $sslverify=true) {
        $this->key = $key;
        $this->secret = $secret;
        $this->url = $url;
        $this->version = $version;
        $this->curl = curl_init();

        curl_setopt_array($this->curl, array(
            CURLOPT_SSL_VERIFYPEER => $sslverify,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_USERAGENT => 'Trt PHP API Agent',
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true)
        );
    }

    function __destruct() {
        curl_close($this->curl);
    }

    function QueryPublic($method, array $request = array()) {
        $postdata = http_build_query($request, '', '&');
        $url = $this->url . '/v' . $this->version . '/' . $method;

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array());

        $callResult = curl_exec($this->curl);
        $httpCode   = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $result     = false;

        if ($httpCode == 200 || $httpCode == 201) {
          $result = json_decode($callResult, true);
        } else {
          $result = " ERR:$httpCode ";
        }

        return $result;
    }

    function QueryPrivate($method, array $request = array(), $custom_method='GET') {
        $path     = '/v' . $this->version . '/' . $method;
        $url      = $this->url.$path;

        $nonce    = ((string)microtime(true) * 10000).'00';
        $sign     = hash_hmac("sha512", $nonce.$url, $this->secret);

        $headers  = array(
          "Content-Type: application/json",
          "X-TRT-KEY: ".$this->key,
          "X-TRT-SIGN: ".$sign,
          "X-TRT-NONCE: ".$nonce
        );

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $custom_method);

        if ($custom_method == "POST" ) {
          curl_setopt($this->curl, CURLOPT_POST,TRUE);
          curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($request));
        }

        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

        $callResult = curl_exec($this->curl);
        $httpCode   = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $result     = false;

        if ($httpCode == 200 || $httpCode == 201) {
          $result = json_decode($callResult, true);
        } else {
          $result = " ERR:$httpCode ";
        }

        return $result;
    }
}
