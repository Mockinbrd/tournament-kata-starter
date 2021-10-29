<?php

namespace App\Controller;

use App\Entity\Tournament;
use Symfony\Component\Uid\Uuid;
use App\Services\TournamentService;
use App\Validator\TournamentValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Uid\Ulid;

class TournamentController extends AbstractController
{
    public function __construct(
        private TournamentService $tournamentService,
        private TournamentValidator $tournamentValidator
    ) {
    }

    /**
     * @Route("/api/tournaments", name="create_tournament", methods={"POST"})
     */
    public function addTournament(Request $request): Response
    {
        $parametersAsArray = json_decode($request->getContent(), true);

        $this->tournamentValidator->validate($parametersAsArray, false);

        $tournament = $this->tournamentService->createTournament($parametersAsArray);

        return $this->json([
            'id' => (string) $tournament->getId(),
        ]);
    }

    /**
     * @Route("/api/tournaments/{id}", name="get_tournament", methods={"GET"})
     */
    public function getTournament(string $id): Response
    {
        return $this->json($this->tournamentService->getTournament($id));
    }

    /**
     * @Route("/api/tournaments/{id}/phases", name="create_tournament_single_bracket_elimination", methods={"POST"})
     */
    public function createSingleBracketEliminationTournament(string $id, Request $request): Response
    {
        $tournament = $this->tournamentService->getTournament($id);

        $parametersAsArray = json_decode($request->getContent(), true);

        $this->tournamentValidator->validate($parametersAsArray);

        return $this->json($this->tournamentService->updateTournament($tournament, $parametersAsArray),RESPONSE::HTTP_CREATED);
    }
}
