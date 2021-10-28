<?php

namespace App\Tests\Acceptance;

use App\Tests\TestService\TournamentTestService;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class TournamentTest extends ApiTestCase
{
    public TournamentTestService $tournamentService;

    public const TOURNAMENT_NAME = 'Rolland Garros';

    public function setUp(): void
    {
        parent::setUp();
        $this->tournamentService = new TournamentTestService();
    }

    public function testTournamentCreation(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/tournaments', [
            'headers' => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            'body' => json_encode(['name' => self::TOURNAMENT_NAME])
        ]);

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse()->toArray();

        $this->assertIsString($response["id"]);
    }

    public function testTournamentCreationShouldEnableToRetrieveAfter(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/tournaments', [
            'headers' => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            'body' => json_encode(['name' => self::TOURNAMENT_NAME])
        ]);

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse()->toArray();

        $this->assertIsString($response["id"]);

        $client->request('GET', '/api/tournaments/' . $response["id"]);
        $this->assertResponseIsSuccessful();
        $response = $client->getResponse()->toArray();
        $this->assertEquals(self::TOURNAMENT_NAME, $response["name"]);
    }

    public function testShouldReturnEmptyIfTournamentDoesNotExist(): void
    {
        static::createClient()->request('GET', '/api/tournaments/123');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testTournamentShouldContainParticipantsData(): void
    {
        $client = static::createClient();

        $tournamentId = $this->tournamentService->createTournament($client);

        $client->request('GET', '/api/tournaments/' . $tournamentId);
    
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['participants' => []]);
    }
}
