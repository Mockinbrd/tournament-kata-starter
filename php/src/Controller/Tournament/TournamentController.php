<?php

namespace App\Controller\Tournament;

use Exception;
use App\Entity\Tournament;
use App\Services\TournamentService;
use App\Services\ParticipantService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Validator\TournamentValidator;
use App\Exception\Tournament\TournamentNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TournamentController extends AbstractController
{
    public function __construct(
        private TournamentValidator $tournamentValidator,
        private ParticipantService $participantService,
        private TournamentService $tournamentService
    ) {
    }

    protected function getTournamentFromId(string $tournamentId): Tournament
    {
        try {
            $tournament = $this->tournamentService->getTournament($tournamentId);
            /* dd($tournament->getParticipants()->toArray()); */
        } catch (Exception $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return $tournament;
    }

    /**
     * @Route("/api/tournaments/{tournamentId}", name="get_tournament", methods={"GET"})
     */
    public function getTournament(string $tournamentId): Response
    {
        return $this->json($this->getTournamentFromId($tournamentId));
    }

    /**
     * @Route("/api/tournaments", name="create_tournament", methods={"POST"})
     */
    public function createTournament(Request $request): Response
    {
        $parametersAsArray = json_decode($request->getContent(), true);

        $this->tournamentValidator->validate($parametersAsArray, false);

        $tournament = $this->tournamentService->createTournament($parametersAsArray);

        return $this->json([
            'id' => (string) $tournament->getId(),
        ]);
    }

    /**
     * @Route("/api/tournaments/{tournamentId}/phases", name="create_tournament_single_bracket_elimination", methods={"POST"})
     */
    public function createSingleBracketEliminationTournament(string $tournamentId, Request $request): Response
    {
        $tournament = $this->getTournamentFromId($tournamentId);

        $parametersAsArray = json_decode($request->getContent(), true);

        $this->tournamentValidator->validate($parametersAsArray);

        return $this->json($this->tournamentService->updateTournament($tournament, $parametersAsArray), RESPONSE::HTTP_CREATED);
    }
}
