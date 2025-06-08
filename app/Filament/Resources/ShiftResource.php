<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\CPGOrganisation;
use App\Filament\Resources\ShiftResource\Pages;
use App\Filament\Resources\ShiftResource\RelationManagers\PatientContactsRelationManager;
use App\Models\Shift;
use DateTime;
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
    public const array paidShiftOrganisations = [
        CPGOrganisation::MEDICORE->value,
        CPGOrganisation::CODEBLUE->value,
    ];

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
                            ->afterStateUpdated(function (Set $set, Get $get): void {
                                $set('end', $get('start'));

                                if ($get('paid_shift')) {
                                    self::calculateInvoiceAmount($set, $get);
                                }
                            })
                            ->required(),
                        DateTimePicker::make('end')
                            ->seconds(false)
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get): void {
                                if ($get('paid_shift')) {
                                    self::calculateInvoiceAmount($set, $get);
                                }
                            })
                            ->required(),
                        TextInput::make('name'),
                        Select::make('organisation')
                            ->options(CPGOrganisation::class)
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get, ?string $state): void {
                                $isPaid = in_array($state, self::paidShiftOrganisations);

                                $set('paid_shift', $isPaid);

                                if ($isPaid) {
                                    self::calculateInvoiceAmount($set, $get);
                                }

                            })
                            ->required(),
                        Checkbox::make('paid_shift')
                            ->default(false)
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get, bool $state): void {
                                if ($state) {
                                    self::calculateInvoiceAmount($set, $get);
                                }
                            }),
                        TextInput::make('invoice_amount')
                            ->numeric()
                            ->visible(fn (Get $get): bool => (bool) $get('paid_shift')),
                        Checkbox::make('invoice_sent')
                            ->visible(fn (Get $get): bool => (bool) $get('paid_shift')),
                        Checkbox::make('invoice_paid')
                            ->visible(fn (Get $get): bool => (bool) $get('paid_shift')),
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
                TextColumn::make('name')
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
            PatientContactsRelationManager::class,
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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ( ! $data['paid_shift']) {
            $data['invoice_amount'] = null;
        }

        return $data;
    }

    private static function calculateInvoiceAmount(Set $set, Get $get): void
    {
        $organisation = $get('organisation');
        $start = $get('start');
        $end = $get('end');

        if ( ! $start || ! $end) {
            return;
        }

        $startDateTime = new DateTime($start);
        $endDateTime = new DateTime($end);

        $durationInSeconds = $endDateTime->getTimestamp() - $startDateTime->getTimestamp();
        $durationInHours = $durationInSeconds / 3600;

        $roundedDuration = ceil($durationInHours * 4) / 4;

        $hourlyRate = match ($organisation) {
            CPGOrganisation::MEDICORE->value => 15.00,
            CPGOrganisation::CODEBLUE->value => 17.00,
            default => 0.00,
        };

        $amount = $roundedDuration * $hourlyRate;

        $set('invoice_amount', $amount);
    }
}
