<?php

namespace App\Controller\Validator;

use App\Model\Tournament;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TournamentValidator
{
    public function validate(array $tournamentData, bool $shouldCheckType = true)
    {
        foreach ($tournamentData as $key => $data) {
            switch ($key) {
                case 'type': 
                    if($shouldCheckType && !in_array($data, Tournament::TYPES)) throw new BadRequestHttpException("Le type n'est pas fourni ou n'est pas connu");
                default:
                    if (!$data) throw new BadRequestHttpException("Le champ nom est manquant ou vide");
            }
        }
    }
}
