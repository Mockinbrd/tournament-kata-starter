<?php

namespace App\Tests\Acceptance;

use App\Tests\WebTestCaseWithDatabase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\TestService\TournamentTestService;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\Uid\Ulid;
use App\DataFixtures\TournamentFixtures;

class ParticipantTest extends WebTestCaseWithDatabase
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
        $this->addFixture(TournamentFixtures::class);
    }

    public function testParticipantCreation(): void
    {
        $this->client->request('POST', '/api/tournaments/' . $this->tournamentService->createTournament($this->client) . '/participants', [
            'headers' => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            'body' => json_encode($this->participant)
        ]);
        $this->assertResponseIsSuccessful();
        $response = $this->client->getResponse()->toArray();

        $this->assertIsString($response["id"]);
    }

    public function testCreateParticipantButTournamentDoesNotExist(): void
    {
        $this->client->request('POST', '/api/tournaments/' . (string) new Ulid() . '/participants', [
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
        $tournamentId = $this->tournamentService->createTournament($this->client);

        $this->client->request('POST', '/api/tournaments/' . $tournamentId . '/participants', [
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
        $tournamentId = $this->tournamentService->createTournament($this->client);

        $this->client->request('POST', '/api/tournaments/' . $tournamentId . '/participants', [
            'headers' => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            'body' => json_encode($this->participant)
        ]);

        $this->assertResponseIsSuccessful();
        $participant = $this->client->getResponse()->toArray();

        $this->client->request('GET', '/api/tournaments/' . $tournamentId . '/participants', [
            'headers' => [
                'Content-Type: application/json',
                'Accept: application/json',
            ]
        ]);
        $this->assertJsonContains(
            [
                [
                    'id' => $participant['id'],
                    'name' => self::PARTICIPANT_NAME,
                    'elo' => self::PARTICIPANT_ELO
                ]
            ]
        );
    }

    public function testParticipantDeletion(): void
    {
        $tournamentId = $this->tournamentService->createTournament($this->client);

        $this->client->request('POST', '/api/tournaments/' . $tournamentId . '/participants', [
            'headers' => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            'body' => json_encode($this->participant)
        ]);

        $participant = $this->client->getResponse()->toArray();

        $this->client->request('DELETE', '/api/tournaments/' . $tournamentId . '/participants/' . $participant['id'], [
            'headers' => [
                'Content-Type: application/json',
                'Accept: application/json',
            ]
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
