<?php

namespace App\Tests\Acceptance;

use App\Tests\WebTestCaseWithDatabase;
use App\Tests\TestService\TournamentTestService;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\Uid\Ulid;

class TournamentTest extends WebTestCaseWithDatabase
{
    public TournamentTestService $tournamentService;

    public const TOURNAMENT_NAME = 'Rolland Garros';
    public const TOURNAMENT_TYPE_SINGLE_BRACKET_ELIMINATION = 'SingleBracketElimination';

    public function setUp(): void
    {
        parent::setUp();
        $this->tournamentService = new TournamentTestService();
    }

    public function testTournamentCreation(): void
    {
        $this->client->request('POST', '/api/tournaments', [
            'headers' => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            'body' => json_encode(['name' => self::TOURNAMENT_NAME])
        ]);

        $this->assertResponseIsSuccessful();
        $response = $this->client->getResponse()->toArray();

        $this->assertIsString($response["id"]);
    }

    public function testTournamentCreationShouldEnableToRetrieveAfter(): void
    {
        $this->client->request('POST', '/api/tournaments', [
            'headers' => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            'body' => json_encode(['name' => self::TOURNAMENT_NAME])
        ]);

        $this->assertResponseIsSuccessful();
        $response = $this->client->getResponse()->toArray();

        $this->assertIsString($response["id"]);

        $this->client->request('GET', '/api/tournaments/' . $response["id"]);
        
        $this->assertResponseIsSuccessful();
        $response = $this->client->getResponse()->toArray();
        $this->assertEquals(self::TOURNAMENT_NAME, $response["name"]);
    }

    public function testShouldReturnEmptyIfTournamentDoesNotExist(): void
    {
        static::createClient()->request('GET', '/api/tournaments/' . (string) new Ulid());

        $this->assertResponseStatusCodeSame(404);
    }

    public function testTournamentShouldContainParticipantsData(): void
    {
        $tournamentId = $this->tournamentService->createTournament($this->client);

        $this->client->request('GET', '/api/tournaments/' . $tournamentId);
    
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['participants' => []]);
    }

    public function testTournamentSingleBracketEliminationType()
    {
        $tournamentId = $this->tournamentService->createTournament($this->client);
        $this->client->request('POST', '/api/tournaments/' . $tournamentId . '/phases', [
            'headers' => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            'body' => json_encode(['type' => self::TOURNAMENT_TYPE_SINGLE_BRACKET_ELIMINATION])
        ]);
        $this->assertResponseIsSuccessful();
    }
} 
