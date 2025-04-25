<?php

declare(strict_types=1);

namespace App\Filament\Resources\CpcItemResource\Pages;

use App\Filament\Resources\CpcItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCpcItems extends ListRecords
{
    protected static string $resource = CpcItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
