<?php

namespace App\Controller;

use App\Model\Participant;
use App\Services\ParticipantService;
use Symfony\Component\Uid\Uuid;
use App\Services\TournamentService;
use Symfony\Component\HttpFoundation\Request;
use App\Services\ParticipantTournamentService;
use App\Validator\ParticipantValidator;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ParticipantController extends AbstractController
{
    public function __construct(
        private TournamentService $tournamentService,
        private ParticipantTournamentService $participantTournamentService,
        private ParticipantService $participantService,
        private ParticipantValidator $participantValidator
    ) {
    }

    /**
     * @Route("/api/tournaments/{tournamentId}/participants/{participantId}", name="delete_tournament_participant", methods={"DELETE"})
     */
    public function deleteParticipant(string $tournamentId, string $participantId): Response
    {
        $tournament = $this->tournamentService->getTournament($tournamentId);
        $participant = $this->participantService->getParticipant($tournament, $participantId);

        $this->participantService->deleteParticipant($tournament, $participant->id);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/api/tournaments/{tournamentId}/participants", name="get_tournament_particpants", methods={"GET"})
     */
    public function getParticipantsOfTournament(string $tournamentId): Response
    {
        $tournament = $this->tournamentService->getTournament($tournamentId);

        return $this->json($tournament->getParticipants());
    }

    /**
     * @Route("/api/tournaments/{tournamentId}/participants", name="create_tournament_participant", methods={"POST"})
     */
    public function createTournamentParticipant(string $tournamentId, Request $request): Response
    {
        $tournament = $this->tournamentService->getTournament($tournamentId);
        $parametersAsArray = json_decode($request->getContent(), true);

        $this->participantValidator->validate($parametersAsArray);

        $uuid = Uuid::v4();

        $participant = new Participant($uuid, $parametersAsArray['name'], $parametersAsArray['elo']);

        $this->participantTournamentService->saveParticipantOnTournament($participant, $tournament);

        return $this->json([
            "id" => $participant->id
        ]);
    }
}
