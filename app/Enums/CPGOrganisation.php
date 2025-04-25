<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CPGOrganisation: string implements HasLabel
{
    case CODEBLUE = 'Codeblue';
    case OMAC = 'Order of Malta';
    case MEDICORE = 'Medicore';
    case NAS = 'National Ambulance Service';
    case NONE = 'None';

    public function getLabel(): ?string
    {
        return $this->value;
    }
}
