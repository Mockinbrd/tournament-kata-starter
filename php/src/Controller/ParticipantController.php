<?php

namespace App\Controller;

use App\Model\Participant;
use Symfony\Component\Uid\Uuid;
use App\Services\TournamentService;
use Symfony\Component\HttpFoundation\Request;
use App\Services\ParticipantTournamentService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ParticipantController extends AbstractController
{
    public function __construct(
        private TournamentService $tournamentService,
        private ParticipantTournamentService $participantTournamentService
    ) {
    }

    /**
     * @Route("/api/tournaments/{tournamentId}/participants", name="create_participant", methods={"POST"})
     */
    public function createTournamentParticipant(string $tournamentId, Request $request): Response
    {
        $tournament = $this->tournamentService->getTournament($tournamentId);

        if (null == $tournament) {
            throw $this->createNotFoundException();
        }

        $parametersAsArray = json_decode($request->getContent(), true);

        if(!isset($parametersAsArray['name']))
        {
            return $this->json([
                "message" => "Le paramètre 'name' est requis"
            ], Response::HTTP_BAD_REQUEST);
        }

        $uuid = Uuid::v4();

        $participant = new Participant($uuid, $parametersAsArray['name'], $parametersAsArray['elo']);

        $this->participantTournamentService->saveParticipantOnTournament($participant, $tournament);

        return $this->json([
            "id" => $participant->id
        ]);
    }
}
