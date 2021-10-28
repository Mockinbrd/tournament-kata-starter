<?php

namespace App\Validator;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ParticipantValidator
{
    public function validate(array $participantData): void
    {
        foreach ($participantData as $key => $data) {
            switch ($key) {
                case 'elo':
                    if (!is_integer($data)) throw new BadRequestHttpException("Le nom (chaine de caractères non vide) ou l'elo (nombre entier) sont incorrects");
                default:
                    if (!$data) throw new BadRequestHttpException("Le nom (chaine de caractères non vide) ou l'elo (nombre entier) sont incorrects");
            }
        }
    }
}
