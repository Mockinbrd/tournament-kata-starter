<?php

namespace App\Validator;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TournamentValidator
{

    public function validate(array $tournamentData)
    {
        foreach ($tournamentData as $key => $data) {
            switch ($key) {
                default:
                    if (!$data) throw new BadRequestHttpException("Le champ nom est manquant ou vide");
            }
        }
    }
}
