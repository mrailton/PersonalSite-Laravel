<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CertificateResource\Pages\CreateCertificate;
use App\Filament\Resources\CertificateResource\Pages\EditCertificate;
use App\Filament\Resources\CertificateResource\Pages\ListCertificates;
use App\Filament\Resources\CertificateResource\Pages\ViewCertificate;
use App\Models\Certificate;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CertificateResource extends Resource
{
    protected static ?string $model = Certificate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'EMT';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('issuer')->required(),
                DatePicker::make('issued_on')->required(),
                DatePicker::make('expires_on')->nullable(),
                TextInput::make('notes')->nullable(),
                FileUpload::make('attachment')->nullable()
                    ->storeFileNamesIn('attachment_name')
                    ->openable()
                    ->downloadable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable(),
                TextColumn::make('issuer')
                    ->sortable(),
                TextColumn::make('issued_on')
                    ->sortable()
                    ->date(),
                TextColumn::make('expires_on')
                    ->sortable()
                    ->date(),
            ])
            ->filters([
                TrashedFilter::make(),
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
            ])
            ->defaultSort('issued_on', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCertificates::route('/'),
            'create' => CreateCertificate::route('/create'),
            'view' => ViewCertificate::route('/{record}'),
            'edit' => EditCertificate::route('/{record}/edit'),
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
