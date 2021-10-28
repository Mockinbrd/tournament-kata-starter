<?php

namespace App\Tests\Acceptance;

use App\Tests\TestService\TournamentTestService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;


class ParticipantTest extends ApiTestCase
{
    public TournamentTestService $tournamentService;

    public function setUp(): void
    {
        parent::setUp();
        $this->tournamentService = new TournamentTestService();
    }

    public function testParticipantCreation(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/tournaments/'. $this->tournamentService->createTournament($client) .'/participants', [
            'headers' => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            'body' => json_encode([
                'name' => 'Novak Djokovic',
                'elo' => 2500
            ])
        ]);
        $this->assertResponseIsSuccessful();
        $response = $client->getResponse()->toArray();

        $this->assertIsString($response["id"]);
    }

    public function testTournamentDoesNotExist(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/tournaments/' . 10 . '/participants', [
            'headers' => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            'body' => json_encode([
                'name' => 'Novak Djokovic',
                'elo' => 2500
            ])
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testParticipantCreationWithoutGoodParameters(): void
    {
        $client = static::createClient();
        $tournamentId = $this->tournamentService->createTournament($client);

        $client->request('POST', '/api/tournaments/' . $tournamentId . '/participants', [
            'headers' => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            'body' => json_encode([
                'prenom' => 'Novak Djokovic',
                'elo' => 2500
            ])
        ]);


        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testListParticipantOfTournament(): void
    {
        
    }
}
