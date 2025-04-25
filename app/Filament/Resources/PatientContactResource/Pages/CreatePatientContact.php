<?php

declare(strict_types=1);

namespace App\Filament\Resources\PatientContactResource\Pages;

use App\Filament\Resources\PatientContactResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePatientContact extends CreateRecord
{
    protected static string $resource = PatientContactResource::class;
}
