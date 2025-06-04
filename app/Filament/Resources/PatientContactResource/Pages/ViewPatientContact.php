<?php

declare(strict_types=1);

namespace App\Filament\Resources\PatientContactResource\Pages;

use App\Filament\Resources\PatientContactResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPatientContact extends ViewRecord
{
    protected static string $resource = PatientContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('viewShift')
                ->label('View Shift')
                ->color('secondary')
                ->url(fn () => route('filament.admin.resources.shifts.view', ['record' => $this->getRecord()->shift_id]))
                ->visible(fn () => filled($this->getRecord()->shift_id)),
            EditAction::make(),
        ];
    }
}
