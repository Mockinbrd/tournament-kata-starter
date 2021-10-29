<?php

namespace App\Tests\TestService;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Tests\Acceptance\TournamentTest;

class TournamentTestService
{
    public function createTournament(Client $client): string
    {
        $client->request('POST', '/api/tournaments', [
            'headers' => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            'body' => json_encode(['name' => TournamentTest::TOURNAMENT_NAME])
        ]);

        $response = $client->getResponse()->toArray();

        return $response['id'];
    }

}