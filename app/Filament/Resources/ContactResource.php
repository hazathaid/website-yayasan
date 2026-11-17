<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Models\Contact;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Pesan';

    protected static ?string $modelLabel = 'Pesan';

    protected static ?string $pluralModelLabel = 'Pesan Masuk';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('is_read')->label('Dibaca')->boolean(),
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('subject')->label('Subjek')->searchable()->limit(45),
                Tables\Columns\TextColumn::make('email')->label('Email')->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->label('Masuk')->dateTime('d M Y H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_read')->label('Sudah dibaca'),
            ])
            ->actions([
                Tables\Actions\Action::make('readMessage')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn (Contact $record) => $record->subject)
                    ->modalContent(fn (Contact $record) => new HtmlString(
                        '<div class="space-y-2 text-sm">'
                        .'<p><strong>Dari:</strong> '.e($record->name).' &lt;'.e($record->email).'&gt;</p>'
                        .($record->phone ? '<p><strong>Telepon:</strong> '.e($record->phone).'</p>' : '')
                        .'<p class="whitespace-pre-line">'.e($record->message).'</p>'
                        .'</div>'
                    ))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),
                Tables\Actions\Action::make('markRead')
                    ->label('Tandai dibaca')
                    ->icon('heroicon-o-check')
                    ->visible(fn (Contact $record) => ! $record->is_read)
                    ->action(fn (Contact $record) => $record->update(['is_read' => true])),
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
            'index' => Pages\ListContacts::route('/'),
        ];
    }
}
