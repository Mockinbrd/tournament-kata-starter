<?php

namespace App\Tests\TestService;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;

class TournamentTestService
{
    public function createTournament(Client $client): string
    {
        $client->request('POST', '/api/tournaments', [
            'headers' => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            'body' => json_encode(['name' => 'Tournament'])
        ]);

        $response = $client->getResponse()->toArray();

        return $response['id'];
    }

}