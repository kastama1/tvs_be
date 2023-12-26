<?php

namespace App\Enum;

enum ElectionTypeEnum: string
{
    case PRESIDENTIAL_ELECTION = 'presidential_election';
    case SENATE_ELECTION = 'senate_election';
    case CHAMBER_OF_DEPUTIES_ELECTION = 'chamber_of_deputies_election';
    case EUROPEAN_PARLIAMENT_ELECTION = 'european_parliament_election';
    case REGIONAL_ASSEMBLY_ELECTION = 'regional_assembly_election';
    case MUNICIPAL_ASSEMBLY_ELECTION = 'municipal_assembly_election';
}
