<?php

namespace App\Service;

use symfony\component\HttpClient\HttpClient;

class ImdbService
{
    public function getImdbData(): array
    {

    $client= HttpClient::create();

    $response= $client->request('GET',"https://imdb-api.com/en/API/Top250TVs/". $_ENV['API_KEY']);

    return $result= $response->toArray();

    

    }
}
