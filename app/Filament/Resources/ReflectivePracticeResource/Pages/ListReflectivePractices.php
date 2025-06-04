<?php

declare(strict_types=1);

namespace App\Filament\Resources\ReflectivePracticeResource\Pages;

use App\Filament\Resources\ReflectivePracticeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReflectivePractices extends ListRecords
{
    protected static string $resource = ReflectivePracticeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
