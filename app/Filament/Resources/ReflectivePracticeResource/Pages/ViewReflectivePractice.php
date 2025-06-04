<?php

declare(strict_types=1);

namespace App\Filament\Resources\ReflectivePracticeResource\Pages;

use App\Filament\Resources\ReflectivePracticeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewReflectivePractice extends ViewRecord
{
    protected static string $resource = ReflectivePracticeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
