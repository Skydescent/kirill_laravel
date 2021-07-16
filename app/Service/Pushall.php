<?php


namespace App\Service;


use GuzzleHttp\Client;

class Pushall
{
    private $id;
    private $apiKey;

    protected $url = "https://pushall.ru/api.php";

    public function __construct($apiKey, $id)
    {
        $this->apiKey = $apiKey;
        $this->id = $id;
    }

    public function send($title, $text)
    {
        $data = [
            'type' => 'self',
            'id' => $this->id,
            'key' => $this->apiKey,
            'text' => $text,
            'title' => $title
        ];

        $client = new Client();
        return $client->post( $this->url, ['form_params' => $data]);

    }
}
