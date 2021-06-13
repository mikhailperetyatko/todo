<?php

namespace App\Services\PS5;

class Eldorado
{
    public $curl;
    public $digitalUrl;
    public $url;
    
    public function __construct()
    {
        $this->curl = curl_init();
        
        //$this->url = 'https://www.technopark.ru/igrovaya-pristavka-sony-playstation-5-cfi-1015a/';
        //$this->url = 'https://www.mvideo.ru/products/igrovaya-konsol-sony-playstation-5-40073270';
        $this->url = 'https://www.svyaznoy.ru/catalog/gamepad/9209/5796227';
        curl_setopt_array($this->curl, [
            CURLOPT_HEADER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_COOKIEJAR => '/var/www/lot_online/ps_5_eldorado.txt',
            CURLOPT_COOKIEFILE => '/var/www/lot_online/ps_5_eldorado.txt',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => [
                'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.212 Safari/537.36',
            ],
        ]);
        $this->session = [];
        
        return $this;
    }
    
    protected function search($pattern, $text, $errorText, $required = false)
    {
        $matches = [];
        preg_match($pattern, $text, $matches);
        if (!isset($matches[0]) && $required) throw new \Exception('Ошибка получения ' . $errorText);

        return isset($matches[0]) ? $matches[0] : '';
    }
            
    protected function doCURL()
    {        
        $response = curl_exec($this->curl);
        
        return $response;
    }
    
    protected function auth()
    {
        curl_setopt_array($this->curl, [
            CURLOPT_URL => 'https://www.eldorado.ru/promo/prm-playstation5/',
            CURLOPT_POST => false,
        ]);
        
        $this->doCURL();
        
        //dSesn
        
        
        //
    }
    
    public function getStatus(): bool
    {
        $this->auth();
        
        curl_setopt_array($this->curl, [
            CURLOPT_URL => $this->url,
            CURLOPT_POST => false,
            
        ]);
        
        dd($this->doCURL());
        
        return false;
    }
    
}
