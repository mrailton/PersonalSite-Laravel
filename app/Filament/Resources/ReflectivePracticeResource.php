<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ReflectivePracticeResource\Pages\CreateReflectivePractice;
use App\Filament\Resources\ReflectivePracticeResource\Pages\EditReflectivePractice;
use App\Filament\Resources\ReflectivePracticeResource\Pages\ListReflectivePractices;
use App\Filament\Resources\ReflectivePracticeResource\Pages\ViewReflectivePractice;
use App\Models\ReflectivePractice;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
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

class ReflectivePracticeResource extends Resource
{
    protected static ?string $model = ReflectivePractice::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Reflective Practice';

    protected static ?string $navigationGroup = 'EMT';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->columns(1)
                    ->schema([
                        TextInput::make('name'),
                        DatePicker::make('date'),
                        Textarea::make('description')
                            ->rows(5),
                        Textarea::make('feelings')
                            ->rows(5),
                        Textarea::make('evaluation')
                            ->rows(5),
                        Textarea::make('analysis')
                            ->rows(5),
                        Textarea::make('conclusion')
                            ->rows(5),
                        Textarea::make('action_plan')
                            ->rows(5),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable(),
                TextColumn::make('date')->sortable()->date(),
            ])
            ->filters([
                TrashedFilter::make(),
                Filter::make('date')
                    ->form([
                        Fieldset::make('Contact Date')
                            ->schema([
                                DatePicker::make('from')->default(now()->subYear()->month(11)->day(1)),
                                DatePicker::make('to')->default(now()->month(10)->day(31)),
                            ])
                            ->columns(1),
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
            'index' => ListReflectivePractices::route('/'),
            'create' => CreateReflectivePractice::route('/create'),
            'view' => ViewReflectivePractice::route('/{record}'),
            'edit' => EditReflectivePractice::route('/{record}/edit'),
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
