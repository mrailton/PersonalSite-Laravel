<?php

declare(strict_types=1);

namespace App\Filament\Resources\CpcItemResource\Pages;

use App\Filament\Resources\CpcItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCpcItem extends CreateRecord
{
    protected static string $resource = CpcItemResource::class;
}
