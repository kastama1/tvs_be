<?php

namespace App\Enum;

enum ElectionVotableTypeEnum: string
{
    case CANDIDATES = 'candidates';
    case ELECTION_PARTIES = 'election_parties';
}
