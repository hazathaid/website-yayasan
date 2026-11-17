<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProgramResource\Pages;
use App\Models\Program;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProgramResource extends Resource
{
    protected static ?string $model = Program::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationGroup = 'Program & Donasi';

    protected static ?string $modelLabel = 'Program';

    protected static ?string $pluralModelLabel = 'Program';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Program')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Nama program')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (string $operation, ?string $state, Set $set) => $operation === 'create' ? $set('slug', Str::slug((string) $state)) : null),
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),
                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi singkat')
                        ->required()
                        ->rows(3)
                        ->maxLength(500)
                        ->columnSpanFull(),
                    Forms\Components\RichEditor::make('content')
                        ->label('Deskripsi lengkap')
                        ->columnSpanFull(),
                    Forms\Components\FileUpload::make('cover_path')
                        ->label('Gambar sampul')
                        ->image()
                        ->disk('public')
                        ->directory('programs')
                        ->imageEditor()
                        ->maxSize(4096)
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Pendanaan & Status')
                ->schema([
                    Forms\Components\TextInput::make('target_amount')
                        ->label('Target donasi')
                        ->numeric()
                        ->prefix('Rp'),
                    Forms\Components\TextInput::make('collected_amount')
                        ->label('Donasi terkumpul (manual/offline)')
                        ->helperText('Donasi terverifikasi yang terhubung ke program ini ditambahkan otomatis.')
                        ->numeric()
                        ->prefix('Rp')
                        ->default(0),
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'active' => 'Berjalan',
                            'completed' => 'Selesai',
                            'paused' => 'Ditunda',
                        ])
                        ->default('active')
                        ->required(),
                    Forms\Components\DatePicker::make('started_at')->label('Tanggal mulai'),
                    Forms\Components\DatePicker::make('ended_at')->label('Tanggal selesai'),
                    Forms\Components\Toggle::make('is_published')->label('Publikasikan')->default(true),
                ])
                ->columns(2),

            Forms\Components\Section::make('Galeri program')
                ->schema([
                    Forms\Components\Repeater::make('images')
                        ->relationship()
                        ->schema([
                            Forms\Components\FileUpload::make('path')
                                ->label('Foto')
                                ->image()
                                ->disk('public')
                                ->directory('programs')
                                ->required(),
                            Forms\Components\TextInput::make('sort_order')
                                ->label('Urutan')
                                ->numeric()
                                ->default(0),
                        ])
                        ->columns(2)
                        ->defaultItems(0)
                        ->reorderableWithButtons()
                        ->addActionLabel('Tambah foto'),
                ])
                ->collapsed(),

            Forms\Components\Section::make('Terjemahan (English)')
                ->schema([
                    Forms\Components\TextInput::make('title_en')->label('Title (EN)')->maxLength(255),
                    Forms\Components\Textarea::make('description_en')->label('Short description (EN)')->rows(3)->maxLength(500),
                    Forms\Components\RichEditor::make('content_en')->label('Full description (EN)')->columnSpanFull(),
                ])
                ->columns(2)
                ->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover_path')->label('Sampul')->disk('public')->height(40)->width(60),
                Tables\Columns\TextColumn::make('title')->label('Program')->searchable()->sortable()->limit(45),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'completed' => 'Selesai',
                        'paused' => 'Ditunda',
                        default => 'Berjalan',
                    })
                    ->color(fn (string $state) => match ($state) {
                        'completed' => 'success',
                        'paused' => 'warning',
                        default => 'info',
                    }),
                Tables\Columns\TextColumn::make('target_amount')->label('Target')->formatStateUsing(fn ($state) => rupiah($state)),
                Tables\Columns\TextColumn::make('collected_amount')->label('Terkumpul')->formatStateUsing(fn ($state) => rupiah($state)),
                Tables\Columns\IconColumn::make('is_published')->label('Publik')->boolean(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'active' => 'Berjalan',
                    'completed' => 'Selesai',
                    'paused' => 'Ditunda',
                ]),
            ])
            ->actions([
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
            'index' => Pages\ListPrograms::route('/'),
            'create' => Pages\CreateProgram::route('/create'),
            'edit' => Pages\EditProgram::route('/{record}/edit'),
        ];
    }
}
