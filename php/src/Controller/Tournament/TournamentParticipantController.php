<?php

namespace App\Controller\Tournament;

use Symfony\Component\HttpFoundation\Request;
use App\Services\ParticipantTournamentService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Validator\ParticipantValidator;
use App\Entity\Tournament;
use App\Services\ParticipantService;
use App\Services\TournamentService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TournamentParticipantController extends AbstractController
{
    public function __construct(
        private ParticipantTournamentService $participantTournamentService,
        private ParticipantValidator $participantValidator,
        private ParticipantService $participantService,
        private TournamentService $tournamentService,
        private NormalizerInterface $normalizer
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
     * @Route("/api/tournaments/{tournamentId}/participants", name="get_tournament_particpants", methods={"GET"})
     */
    public function getParticipantsOfTournament(string $tournamentId): Response
    {
        $tournament = $this->getTournamentFromId($tournamentId);

        return $this->json($this->normalizer->normalize($tournament->getParticipants()->toArray(), 'json', ['groups' => ['participant:read']]));
    }

    /**
     * @Route("/api/tournaments/{tournamentId}/participants", name="create_tournament_participant", methods={"POST"})
     */
    public function createTournamentParticipant(string $tournamentId, Request $request): Response
    {
        $tournament = $this->getTournamentFromId($tournamentId);

        $parametersAsArray = json_decode($request->getContent(), true);

        $this->participantValidator->validate($parametersAsArray);

        $participant = $this->participantTournamentService->addParticipantOnTournament($tournament, $parametersAsArray);

        return $this->json([
            "id" => (string) $participant->getId()
        ]);
    }

    /**
     * @Route("/api/tournaments/{tournamentId}/participants/{participantId}", name="delete_tournament_participant", methods={"DELETE"})
     */
    public function deleteParticipantFromTournament(string $tournamentId, string $participantId): Response
    {
        try {
            $tournament = $this->getTournamentFromId($tournamentId);
            $participant = $this->participantService->getParticipant($tournament, $participantId);
        } catch (Exception $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        $this->participantTournamentService->deleteParticipantFromTournament($tournament, $participant);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
