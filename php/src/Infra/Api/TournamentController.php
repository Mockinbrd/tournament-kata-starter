<?php

namespace App\Infra\Api;

use App\Domain\Model\Tournament\Validator\TournamentValidator;
use App\Domain\Port\Input\Commands\CreateTournamentCommand;
use App\Domain\Port\Input\Queries\GetTournamentQuery;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TournamentController extends AbstractController
{
    public function __construct(
        private GetTournamentQuery $getTournamentQuery,
        private TournamentValidator $tournamentValidator,
        private CreateTournamentCommand $createTournamentCommand
    ) {
    }

    /**
     * @Route("/api/tournaments/{tournamentId}", name="get_tournament", methods={"GET"})
     */
    public function getTournament(string $tournamentId): Response
    {
        try {
            $tournament = $this->getTournamentQuery->execute($tournamentId);
        } catch (\Exception $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return $this->json($tournament);
    }

    /**
     * @Route("/api/tournaments", name="create_tournament", methods={"POST"})
     */
    public function createTournament(Request $request): Response
    {
        try {
            $parametersAsArray = json_decode($request->getContent(), true);
            $this->tournamentValidator->validate($parametersAsArray, false);
            $tournament = $this->createTournamentCommand->execute($parametersAsArray);
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        return $this->json([
            'id' => (string) $tournament->getId(),
        ]);
    }
}
