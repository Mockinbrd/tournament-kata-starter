<?php

namespace App\Domain\Model\Tournament\Validator;

use App\Domain\Model\Tournament\Exception\TournamentNotValidException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Domain\Model\Tournament\Tournament;

class TournamentValidator
{
    public function validate(array $tournamentData, bool $shouldCheckType = true)
    {
        foreach ($tournamentData as $key => $data) {
            switch ($key) {
                case 'name':
                    if (!is_string($data)) throw new TournamentNotValidException();
                case 'type':
                    if ($shouldCheckType && !in_array($data, Tournament::TYPES)) throw new TournamentNotValidException();
                default:
                    throw new TournamentNotValidException();
            }
        }
    }
}
