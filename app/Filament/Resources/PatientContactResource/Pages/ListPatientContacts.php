<?php

declare(strict_types=1);

namespace App\Filament\Resources\PatientContactResource\Pages;

use App\Filament\Resources\PatientContactResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPatientContacts extends ListRecords
{
    protected static string $resource = PatientContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
