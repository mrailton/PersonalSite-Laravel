<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\CPCItemTypes;
use App\Filament\Resources\CpcItemResource\Pages\CreateCpcItem;
use App\Filament\Resources\CpcItemResource\Pages\EditCpcItem;
use App\Filament\Resources\CpcItemResource\Pages\ListCpcItems;
use App\Filament\Resources\CpcItemResource\Pages\ViewCpcItem;
use App\Models\CpcItem;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
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
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CpcItemResource extends Resource
{
    protected static ?string $model = CpcItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'EMT CPC';
    protected static ?string $label = 'CPC Item';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->columns(1)
                    ->schema([
                        Select::make('item_type')
                            ->options(CPCItemTypes::class)
                            ->required(),
                        DatePicker::make('date')
                            ->required(),
                        TextInput::make('name')
                            ->label('Name of Programme / Seminar / E-Learning etc')
                            ->required(),
                        Textarea::make('topics')
                            ->label('What topics were covered?')
                            ->rows(5)
                            ->required(),
                        Textarea::make('key_learning_outcomes')
                            ->label('What were your key learning outcomes?')
                            ->rows(5)
                            ->required(),
                        TextInput::make('points')->numeric()
                            ->label('CPC points claimed')
                            ->required(),
                        Textarea::make('practice_change')
                            ->label('If this programme will change your practice, how?')
                            ->rows(5)
                            ->required(),
                        FileUpload::make('attachment')
                            ->required()
                            ->storeFileNamesIn('attachment_name')
                            ->openable()
                            ->downloadable()
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('item_type')
                    ->sortable(),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('points')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
                Filter::make('date')
                    ->form([
                        Fieldset::make('Course Date')
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
            'index' => ListCpcItems::route('/'),
            'create' => CreateCpcItem::route('/create'),
            'view' => ViewCpcItem::route('/{record}'),
            'edit' => EditCpcItem::route('/{record}/edit'),
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
