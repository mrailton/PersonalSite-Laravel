<?php

declare(strict_types=1);

namespace App\Filament\Resources\ShiftResource\RelationManagers;

use App\Filament\Resources\PatientContactResource;
use App\Models\PatientContact;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PatientContactsRelationManager extends RelationManager
{
    protected static string $relationship = 'patientContacts';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()->columns(1)
                    ->schema([
                        TextInput::make('incident_number')
                            ->required(),
                        Textarea::make('injury')
                            ->rows(5)
                            ->required(),
                        Textarea::make('treatment')
                            ->rows(5)
                            ->required(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('incident_number')
            ->columns([
                TextColumn::make('incident_number'),
            ])
            ->headerActions([
                CreateAction::make()->mutateFormDataUsing(function (array $data) {
                    $data['organisation'] = $this->getOwnerRecord()->organisation;
                    $data['date'] = $this->getOwnerRecord()->start->format('Y-m-d');

                    return $data;
                }),
            ])
            ->actions([
                Action::make('view')->url(fn (PatientContact $record): string => PatientContactResource::getUrl('view', ['record' => $record])),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
