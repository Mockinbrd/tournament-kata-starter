<?php

namespace App\Controller;

use App\Model\Tournament;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\TournamentService;
use App\Validator\TournamentValidator;
use Symfony\Component\Uid\Uuid;

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

        $this->tournamentValidator->validate($parametersAsArray);

        $uuid = Uuid::v4();
        $tournament = new Tournament($uuid, $parametersAsArray["name"]);

        $this->service->saveTournament($tournament);

        return $this->json([
            'id' => $uuid,
        ]);
    }

    /**
     * @Route("/api/tournaments/{id}", name="get_tournament", methods={"GET"})
     */
    public function getTournament(string $id): Response
    {
        return $this->json($this->service->getTournament($id));
    }
}
