<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\CPGOrganisation;
use App\Filament\Resources\ShiftResource\Pages;
use App\Models\Shift;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShiftResource extends Resource
{
    protected static ?string $model = Shift::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'EMT';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->columns(1)
                    ->schema([
                        DateTimePicker::make('start')
                            ->seconds(false)
                            ->live()
                            ->afterStateUpdated(fn (Set $set, Get $get) => $set('end', $get('start'))),
                        DateTimePicker::make('end')
                            ->seconds(false),
                        Select::make('organisation')
                            ->options(CPGOrganisation::class)
                            ->required(),
                        Checkbox::make('paid_shift')
                            ->default(false)
                            ->live(),
                        TextInput::make('invoice_amount')
                            ->visible(fn (Get $get): bool => null !== $get('paid_shift') && $get('paid_shift')),
                        Checkbox::make('invoice_sent')
                            ->visible(fn (Get $get): bool => null !== $get('paid_shift') && $get('paid_shift')),
                        Checkbox::make('invoice_paid')
                            ->visible(fn (Get $get): bool => null !== $get('paid_shift') && $get('paid_shift')),
                        Textarea::make('notes')
                            ->rows(5)
                            ->nullable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('start')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
                TextColumn::make('end')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
                TextColumn::make('organisation')->label('Organisation'),
                TextColumn::make('invoice_amount')->money('eur'),
                IconColumn::make('invoice_sent')
                    ->boolean(),
                IconColumn::make('invoice_paid')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                SelectFilter::make('organisation')
                    ->options(CPGOrganisation::class),
                Filter::make('date')
                    ->form([
                        Fieldset::make('Shift Date')
                            ->schema([
                                DatePicker::make('from')->default(now()->subYear()->month(11)->day(1)),
                                DatePicker::make('to')->default(now()->month(10)->day(31)),
                            ])
                            ->columns(1),
                    ])
                    ->query(fn (Builder $query, array $data) => $query
                        ->when(
                            $data['from'] ?? null,
                            fn (Builder $query) => $query->whereDate('start', '>=', $data['from'])
                        )
                        ->when(
                            $data['to'] ?? null,
                            fn (Builder $query) => $query->whereDate('end', '<=', $data['to'])
                        )),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShifts::route('/'),
            'create' => Pages\CreateShift::route('/create'),
            'view' => Pages\ViewShift::route('/{record}'),
            'edit' => Pages\EditShift::route('/{record}/edit'),
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
