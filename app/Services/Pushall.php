<?php

namespace App\Services;

use App\Notifications\PushallNotification;

class Pushall
{
    private $id;
    private $key;
    protected $uri;
    protected $type;
    protected $push;
    
    public function __construct($id, $key, $uri, string $type = 'self')
    {
        $this->id = $id;
        $this->key = $key;
        $this->uri = $uri;
        $this->type = $type;
    }
    
    public function send(PushallNotification $push)
    {
        $data = [
            'type' => $this->type,
            'id' => $this->id,
            'key' => $this->key,
            'text' => $push->compile(),
            'title' => $push->title
        ];
        
        $client = new \GuzzleHttp\Client([
            'baze_uri' => $this->uri    
        ]);
        return $client->post($this->uri, ['form_params' => $data]);
    }
}
