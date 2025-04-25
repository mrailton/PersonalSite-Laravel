<?php

declare(strict_types=1);

namespace App\Filament\Resources\PatientContactResource\Pages;

use App\Filament\Resources\PatientContactResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPatientContact extends ViewRecord
{
    protected static string $resource = PatientContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
