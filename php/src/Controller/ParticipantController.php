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
     * @Route("/api/tournaments/{tournamentId}/participants", name="get_tournament_particpants", methods={"GET"})
     */
    public function getParticipantsOfTournament(string $tournamentId): Response
    {
        $tournament = $this->tournamentService->getTournament($tournamentId);

        if (null == $tournament) {
            throw $this->createNotFoundException("Le tournoi n'existe pas");
        }

        return $this->json($tournament->getParticipants());
    }

    /**
     * @Route("/api/tournaments/{tournamentId}/participants", name="create_tournament_participant", methods={"POST"})
     */
    public function createTournamentParticipant(string $tournamentId, Request $request): Response
    {
        $tournament = $this->tournamentService->getTournament($tournamentId);

        if (null == $tournament) {
            throw $this->createNotFoundException("Le tournoi n'existe pas");
        }

        $parametersAsArray = json_decode($request->getContent(), true);

        if(!isset($parametersAsArray['name']) || (isset($parametersAsArray['elo']) && !is_integer($parametersAsArray['elo'])))
        {
            return $this->json([
                "message" => "Le nom 'name' (chaine de caractères non vide) ou l'elo (nombre entier) sont incorrects"
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
