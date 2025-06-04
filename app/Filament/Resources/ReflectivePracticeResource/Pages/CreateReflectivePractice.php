<?php

declare(strict_types=1);

namespace App\Filament\Resources\ReflectivePracticeResource\Pages;

use App\Filament\Resources\ReflectivePracticeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReflectivePractice extends CreateRecord
{
    protected static string $resource = ReflectivePracticeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
