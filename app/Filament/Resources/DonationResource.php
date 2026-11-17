<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationResource\Pages;
use App\Models\Donation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DonationResource extends Resource
{
    protected static ?string $model = Donation::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Program & Donasi';

    protected static ?string $modelLabel = 'Donasi';

    protected static ?string $pluralModelLabel = 'Donasi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data Donatur')
                ->schema([
                    Forms\Components\TextInput::make('donor_name')->label('Nama donatur')->required()->maxLength(120),
                    Forms\Components\TextInput::make('email')->label('Email')->email()->required()->maxLength(150),
                    Forms\Components\TextInput::make('phone')->label('No. HP')->maxLength(30),
                    Forms\Components\Select::make('program_id')
                        ->label('Program')
                        ->relationship('program', 'title')
                        ->searchable()
                        ->preload()
                        ->placeholder('Donasi umum'),
                    Forms\Components\TextInput::make('amount')->label('Nominal')->numeric()->prefix('Rp')->required(),
                    Forms\Components\Select::make('method')
                        ->label('Metode')
                        ->options(['transfer' => 'Transfer bank', 'cash' => 'Tunai'])
                        ->default('transfer')
                        ->required(),
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'pending' => 'Menunggu',
                            'verified' => 'Terverifikasi',
                            'rejected' => 'Ditolak',
                        ])
                        ->default('pending')
                        ->required(),
                    Forms\Components\Textarea::make('note')->label('Catatan')->rows(2)->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('donor_name')->label('Donatur')->searchable()->sortable()->limit(30),
                Tables\Columns\TextColumn::make('program.title')->label('Program')->placeholder('Umum')->limit(30)->toggleable(),
                Tables\Columns\TextColumn::make('amount')->label('Nominal')->formatStateUsing(fn ($state) => rupiah($state))->sortable(),
                Tables\Columns\TextColumn::make('method')->label('Metode')->badge()->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                        default => 'Menunggu',
                    })
                    ->color(fn (string $state) => match ($state) {
                        'verified' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('verified_at')->label('Diverifikasi')->dateTime('d M Y H:i')->placeholder('-'),
                Tables\Columns\TextColumn::make('created_at')->label('Masuk')->dateTime('d M Y H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Menunggu',
                    'verified' => 'Terverifikasi',
                    'rejected' => 'Ditolak',
                ]),
            ])
            ->actions([
                Tables\Actions\Action::make('proof')
                    ->label('Bukti')
                    ->icon('heroicon-o-paper-clip')
                    ->url(fn (Donation $record) => route('donations.proof', $record))
                    ->openUrlInNewTab()
                    ->visible(fn (Donation $record) => filled($record->proof_path)),
                Tables\Actions\Action::make('verify')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Donation $record) => $record->status !== 'verified')
                    ->action(fn (Donation $record) => $record->update([
                        'status' => 'verified',
                        'verified_by' => auth()->id(),
                        'verified_at' => now(),
                    ])),
                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Donation $record) => $record->status !== 'rejected')
                    ->action(fn (Donation $record) => $record->update([
                        'status' => 'rejected',
                        'verified_by' => auth()->id(),
                        'verified_at' => now(),
                    ])),
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
            'index' => Pages\ListDonations::route('/'),
            'create' => Pages\CreateDonation::route('/create'),
            'edit' => Pages\EditDonation::route('/{record}/edit'),
        ];
    }
}
