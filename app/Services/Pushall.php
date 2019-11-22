<?php

namespace App\Services;

class Pushall
{
    private $id;
    private $key;
    protected $uri;
    protected $type;
    
    public function __construct($id, $key, $uri, string $type = 'self')
    {
        $this->id = $id;
        $this->key = $key;
        $this->uri = $uri;
        $this->type = $type;
    }
    
    public function getPostPushData(PushallNotification $push)
    {
        return [
            'type' => $this->type,
            'id' => $this->id,
            'key' => $this->key,
            'text' => $push->compile(),
            'title' => $push->title,
            'url' => $push->getUrlForRedirect(),
        ];
    }
        
    public function send(PushallNotification $push, $method = 'getPostPushData')
    {
        $data = $this->$method($push);
        $client = new \GuzzleHttp\Client([
            'baze_uri' => $this->uri    
        ]);
        return $client->post($this->uri, ['form_params' => $data]);
    }
}
