<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DownloadResource\Pages;
use App\Models\Download;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DownloadResource extends Resource
{
    protected static ?string $model = Download::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?string $modelLabel = 'Unduhan';

    protected static ?string $pluralModelLabel = 'Unduhan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Dokumen')
                ->schema([
                    Forms\Components\TextInput::make('title')->label('Judul')->required()->maxLength(255),
                    Forms\Components\TextInput::make('title_en')->label('Title (EN)')->maxLength(255),
                    Forms\Components\Textarea::make('description')->label('Deskripsi')->rows(2)->columnSpanFull(),
                    Forms\Components\Textarea::make('description_en')->label('Description (EN)')->rows(2)->columnSpanFull(),
                    Forms\Components\FileUpload::make('file_path')
                        ->label('File dokumen')
                        ->disk('public')
                        ->directory('downloads')
                        ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                        ->maxSize(10240)
                        ->required()
                        ->columnSpanFull(),
                    Forms\Components\Toggle::make('is_published')->label('Publikasikan')->default(true),
                    Forms\Components\TextInput::make('sort_order')->label('Urutan')->numeric()->default(0),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable()->sortable()->limit(50),
                Tables\Columns\TextColumn::make('file_path')->label('File')->formatStateUsing(fn (string $state) => basename($state))->limit(40),
                Tables\Columns\IconColumn::make('is_published')->label('Publik')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->label('Urutan')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->label('Diperbarui')->dateTime('d M Y'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')->label('Status publik'),
            ])
            ->actions([
                Tables\Actions\Action::make('open')
                    ->label('Buka')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Download $record) => media_url($record->file_path))
                    ->openUrlInNewTab()
                    ->visible(fn (Download $record) => filled($record->file_path)),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDownloads::route('/'),
            'create' => Pages\CreateDownload::route('/create'),
            'edit' => Pages\EditDownload::route('/{record}/edit'),
        ];
    }
}
