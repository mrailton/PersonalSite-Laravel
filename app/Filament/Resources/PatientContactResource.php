<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\CPGOrganisation;
use App\Filament\Resources\PatientContactResource\Pages\CreatePatientContact;
use App\Filament\Resources\PatientContactResource\Pages\EditPatientContact;
use App\Filament\Resources\PatientContactResource\Pages\ListPatientContacts;
use App\Filament\Resources\PatientContactResource\Pages\ViewPatientContact;
use App\Models\PatientContact;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PatientContactResource extends Resource
{
    protected static ?string $model = PatientContact::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'EMT';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()->columns(1)
                    ->schema([
                        DatePicker::make('date')
                            ->required(),
                        TextInput::make('incident_number')
                            ->required(),
                        Select::make('organisation')
                            ->options(CPGOrganisation::class)
                            ->required(),
                        Textarea::make('injury')
                            ->rows(5)
                            ->required(),
                        Textarea::make('treatment')
                            ->rows(5)
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('organisation')
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('organisation')
                    ->options(CPGOrganisation::class),
                Filter::make('date')
                    ->form([
                        Fieldset::make('Contact Date')
                            ->schema([
                                DatePicker::make('from')->default(now()->subYear()->month(11)->day(1)),
                                DatePicker::make('to')->default(now()->month(10)->day(31))
                            ])
                            ->columns(1)
                    ])
                    ->query(fn (Builder $query, array $data) => $query
                        ->when(
                            $data['from'] ?? null,
                            fn (Builder $query) => $query->whereDate('date', '>=', $data['from'])
                        )
                        ->when(
                            $data['to'] ?? null,
                            fn (Builder $query) => $query->whereDate('date', '<=', $data['to'])
                        )),
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPatientContacts::route('/'),
            'create' => CreatePatientContact::route('/create'),
            'view' => ViewPatientContact::route('/{record}'),
            'edit' => EditPatientContact::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
