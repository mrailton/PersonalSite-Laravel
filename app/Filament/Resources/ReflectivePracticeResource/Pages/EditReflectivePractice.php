<?php

declare(strict_types=1);

namespace App\Filament\Resources\ReflectivePracticeResource\Pages;

use App\Filament\Resources\ReflectivePracticeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReflectivePractice extends EditRecord
{
    protected static string $resource = ReflectivePracticeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
