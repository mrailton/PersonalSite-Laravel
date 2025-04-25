<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CPCItemTypes: string implements HasLabel
{
    case ADDITIONAL_CASE_STUDY = 'Additional Case Study';
    case ADDITIONAL_REFLECTION = 'Additional Reflection';
    case BEING_MENTORED = 'Being Mentored';
    case ELECTRONIC_LEARNING = 'Electronic Learning';
    case INSTRUCTOR = 'Instructor';
    case MENTORING_A_STUDENT = 'Mentoring a Student';
    case OTHER = 'Other';
    case SEMINARS_AND_CONFERENCES = 'Seminars and Conferences';
    case ACLS_PALS_MIMMS_ETC = 'ACLS, PALS, MIMMS etc';
    case CPC_RELATED_PROGRAMMES = 'CPC Related Programmes';

    public function getLabel(): ?string
    {
        return $this->value;
    }
}
