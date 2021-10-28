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

    public const PARTICIPANT_NAME = 'Novak Djokovic';
    public const PARTICIPANT_ELO = 2500;

    public function setUp(): void
    {
        parent::setUp();
        $this->tournamentService = new TournamentTestService();
        $this->participant = [
            'name' => self::PARTICIPANT_NAME,
            'elo' => self::PARTICIPANT_ELO
        ];
    }

    public function testParticipantCreation(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/tournaments/'. $this->tournamentService->createTournament($client) .'/participants', [
            'headers' => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            'body' => json_encode($this->participant)
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
            'body' => json_encode($this->participant)
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
                'prenom' => 'Test',
                'elo' => 'hihi'
            ])
        ]);


        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testListParticipantOfTournament(): void
    {
        $client = static::createClient();
        $tournamentId = $this->tournamentService->createTournament($client);

        $client->request('POST', '/api/tournaments/'. $tournamentId .'/participants', [
            'headers' => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            'body' => json_encode($this->participant)
        ]);

        $this->assertResponseIsSuccessful();
        $participant = $client->getResponse()->toArray();

        $client->request('GET', '/api/tournaments/'. $tournamentId .'/participants', [
            'headers' => [
                'Content-Type: application/json',
                'Accept: application/json',
            ]
        ]);
    
        $this->assertResponseIsSuccessful();
        $response = $client->getResponse()->toArray();

        $this->assertEquals(
        [
            [
                'id' => $participant['id'],
                'name' => self::PARTICIPANT_NAME,
                'elo' => self::PARTICIPANT_ELO
            ]
        ], 
        $response
    );
    }

    public function testParticipantDeletion(): void
    {
        $client = static::createClient();
        $tournamentId = $this->tournamentService->createTournament($client);

        $client->request('POST', '/api/tournaments/'. $tournamentId .'/participants', [
            'headers' => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            'body' => json_encode($this->participant)
        ]);
        
        $participant = $client->getResponse()->toArray();

        $client->request('DELETE', '/api/tournaments/' . $tournamentId . '/participants/' . $participant['id'], [
            'headers' => [
                'Content-Type: application/json',
                'Accept: application/json',
            ]
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
