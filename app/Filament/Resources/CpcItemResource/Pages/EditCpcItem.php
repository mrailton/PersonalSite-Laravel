<?php

declare(strict_types=1);

namespace App\Filament\Resources\CpcItemResource\Pages;

use App\Filament\Resources\CpcItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCpcItem extends EditRecord
{
    protected static string $resource = CpcItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
