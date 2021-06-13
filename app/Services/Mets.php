<?php

namespace App\Services;

class Mets
{
    public $login;
    public $password;
    public $ch;
    public $session;

    public function __construct(string $login, string $password)
    {
        if ($login)
            $this->login = $login;
        if ($password)
            $this->password = $password;

        $this->ch = curl_init();
        curl_setopt_array($this->ch, [
            CURLOPT_HEADER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36',
            ],
        ]);

        return $this;
    }

    public function setValues(array $values)
    {
        foreach ($values as $name => $value) {
            $this->setValue($name, $value);
        }

        return $this;
    }

    public function setValue(string $name, $value)
    {
        if (isset($this->$name))
            $this->$name = $value;

        return $this;
    }

    public function setSession(string $session)
    {
        $this->session = $session;
        curl_setopt_array($this->ch, [
            CURLOPT_COOKIE => $this->session,
        ]);

        return $this;
    }

    public function getValue(string $name)
    {
        if (isset($this->$name))
            return $this->$name;

        return null;
    }

    protected function doCURL(string $errorText)
    {
        $response = curl_exec($this->ch);
        if (!$response)
            throw new \Exception($errorText);

        return $response;
    }

    protected function search($pattern, $text, $errorText, $required = false)
    {
        $matches = [];
        preg_match($pattern, $text, $matches);
        if (!isset($matches[0]) && $required) throw new \Exception('Ошибка получения ' . $errorText);

        return isset($matches[0]) ? $matches[0] : '';
    }

    protected function getSession(string $response = '')
    {
        if ($response)
            return $this->search('/JSESSIONID=\w{1,};/', $response, 'JSESSIONID');
        else
            return $this->session;
    }

    public function auth()
    {
        if (!$this->login || !$this->password)
            throw new \Exception('Логин или пароль не заданы!' );

        curl_setopt_array($this->ch, [
            CURLOPT_URL => 'https://m-ets.ru/authorize',
            CURLOPT_POSTFIELDS => [
                'uri' => 'main',
                'login' => $this->login,
                'password' => $this->password,
            ],
        ]);

        $response = $this->doCURL('Ошибка при авторизации');
        $this->setSession($this->getSession($response));

        return $this;
    }

    public function isAuth()
    {
        if (!$this->session)
            throw new \Exception('Сессия не задана' );

        curl_setopt_array($this->ch, [
            CURLOPT_URL => 'https://m-ets.ru/cabinet',
            CURLOPT_POST => false,
        ]);

        $response = $this->doCURL('Ошибка при проверке авторизации');

        return strpos($response, 'Добро пожаловать,') !== false;
    }

    public function getParticipationAmount(string $id, int $lot) : array
    {
        if (!$this->session)
            throw new \Exception('Сессия не задана' );

        if (!$this->isAuth())
            $this->auth();

        curl_setopt_array($this->ch, [
            CURLOPT_URL => 'https://m-ets.ru/generalView?id=' . $id,
            CURLOPT_COOKIE => $this->session,
            CURLOPT_POST => false,

        ]);

        $response = $this->doCURL('Ошибка при получении страницы торгов');
        $table = $this->search('/<div class="gray-block gray-block-lot">.*?<table class="commontable tworows lottab">.*?<a name="lot' . $lot . '".*?<\/table.*?>.*?<\/div>/si', $response, 'Table');
        $tr = $this->search('/<tr class="lot__info__tr">[^tr]{1,}<td>Количество заявок[^tr]{1,}<\/td>[^tr]{1,}<td>[0-9]{1,}<\/td>[^tr]{1,}<\/tr>/si', $table, 'Tr');
        $amount = $this->search('/[0-9]{1,}/', $tr, 'Amount');
        $trName = $this->search('/<tr>[^tr]{1,}<td>Краткие сведения об имуществе[^tr]{1,}<\/td>[^tr]{1,}<td>.*?<\/td>[^tr]{1,}<\/tr>/si', $table, 'Tr');
        $name = strip_tags($this->search('/<td>((?!Краткие).)*<\/td>/', $trName, 'Name'));
        
        return ['amount' => $amount, 'name' => $name, 'table' => $table];
    }
    
    public function getBiddings()
    {
      if (!$this->session)
            throw new \Exception('Сессия не задана' );

      if (!$this->isAuth())
          $this->auth();

      curl_setopt_array($this->ch, [
          CURLOPT_URL => 'https://m-ets.ru/cabinet',
          CURLOPT_COOKIE => $this->session,
          CURLOPT_POST => false,

      ]);
      
      $response = $this->doCURL('Ошибка при получении страницы торгов');
      $biddings = [];
      preg_match_all('/generalView\?id=[0-9]{9,11}/', $response, $biddings);
      $biddings = collect($biddings[0])->unique();
      $partiCipationsList = collect();
      foreach ($biddings as $key => $bidding) {
        $id = str_replace('generalView?id=', '', $bidding);
        
        curl_setopt_array($this->ch, [
          CURLOPT_URL => 'https://m-ets.ru/showPartiCipation?comp_id=' . $id,
          CURLOPT_COOKIE => $this->session,
          CURLOPT_POST => false,
        ]);
        
        $response = $this->doCURL('Ошибка при получении страницы торгов');
        $partiCipations = [];
        preg_match_all('/showPartiCipationOne\?id=[0-9]{9,11}/', $response, $partiCipations);
        $partiCipations = collect($partiCipations[0])->unique()->map(function($item){
          return str_replace('showPartiCipationOne?id=', '', $item);
        });
        $partiCipationsList = $partiCipationsList->merge($partiCipations);
      }
      
      $users = collect();
      
      foreach ($partiCipationsList as $key => $id) {
        curl_setopt_array($this->ch, [
          CURLOPT_URL => 'https://m-ets.ru/showPartiCipationOne?id=' . $id,
          CURLOPT_COOKIE => $this->session,
          CURLOPT_POST => false,
        ]);
        
        $response = $this->doCURL('Ошибка при получении страницы заявки');
        preg_match_all('#(?:Полное наименование|ФИО)(.+?)<\/tr>#su', $response, $name);
        $name = trim(strip_tags($name[1][0]));
        
        preg_match_all('#ИНН(.+?)<\/tr>#su', $response, $inn);
        $inn = trim(strip_tags($inn[1][0]));
        
        preg_match_all('#Контактный телефон(.+?)<\/tr>#su', $response, $phone);
        $phone = trim(strip_tags($phone[1][0]));
        
        preg_match_all('#Адрес электронной почты(.+?)<\/tr>#su', $response, $email);
        $email = trim(strip_tags($email[1][0]));
        
        preg_match_all('#(?:Почтовый адрес|Сведения о месте жительства)(.+?)<\/tr>#su', $response, $adress);
        $adress = trim(strip_tags($adress[1][0]));
        
        $users[] = [
          'name' => $name,
          'inn' => $inn,
          'phone' => $phone,
          'email' => $email,
          'adress' => $adress,
        ];
      }
      return $users; 
    }
}
