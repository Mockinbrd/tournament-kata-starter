<?php

namespace App\Controller;

use Exception;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;
use App\Services\TournamentService;
use App\Services\ParticipantService;
use App\Validator\ParticipantValidator;
use Symfony\Component\HttpFoundation\Request;
use App\Services\ParticipantTournamentService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ParticipantController extends AbstractController
{
    public function __construct(
        private TournamentService $tournamentService,
        private ParticipantTournamentService $participantTournamentService,
        private ParticipantService $participantService,
        private ParticipantValidator $participantValidator,
        private NormalizerInterface $normalizer
    ) {
    }

    /**
     * @Route("/api/tournaments/{tournamentId}/participants/{participantId}", name="delete_tournament_participant", methods={"DELETE"})
     */
    public function deleteParticipant(string $tournamentId, string $participantId): Response
    {
        $tournament = $this->tournamentService->getTournament($tournamentId);
        $participant = $this->participantService->getParticipant($tournament, $participantId);

        $this->participantTournamentService->deleteParticipantFromTournament($tournament, $participant);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/api/tournaments/{tournamentId}/participants", name="get_tournament_particpants", methods={"GET"})
     */
    public function getParticipantsOfTournament(string $tournamentId): Response
    {
        $tournament = $this->tournamentService->getTournament($tournamentId);
        /* dd($tournament->getParticipants()->toArray()); */

        return $this->json($this->normalizer->normalize($tournament->getParticipants()->toArray(), 'json', ['groups' => ['participant:read']]));
    }

    /**
     * @Route("/api/tournaments/{tournamentId}/participants", name="create_tournament_participant", methods={"POST"})
     */
    public function createTournamentParticipant(string $tournamentId, Request $request): Response
    {
        $tournament = $this->tournamentService->getTournament($tournamentId);
        $parametersAsArray = json_decode($request->getContent(), true);

        $this->participantValidator->validate($parametersAsArray);

        $participant = $this->participantTournamentService->addParticipantOnTournament($tournament, $parametersAsArray);

        return $this->json([
            "id" => (string) $participant->getId()
        ]);
    }
}
