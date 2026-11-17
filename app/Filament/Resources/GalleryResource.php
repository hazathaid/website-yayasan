<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryResource\Pages;
use App\Models\Gallery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?string $modelLabel = 'Galeri';

    protected static ?string $pluralModelLabel = 'Galeri';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Album')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Judul album')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (string $operation, ?string $state, Set $set) => $operation === 'create' ? $set('slug', Str::slug((string) $state)) : null),
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),
                    Forms\Components\Select::make('type')
                        ->label('Tipe')
                        ->options(['foto' => 'Foto', 'video' => 'Video'])
                        ->default('foto')
                        ->required(),
                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi')
                        ->rows(3)
                        ->columnSpanFull(),
                    Forms\Components\FileUpload::make('cover_path')
                        ->label('Sampul album')
                        ->image()
                        ->disk('public')
                        ->directory('galleries')
                        ->maxSize(4096)
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('title_en')->label('Title (EN)')->maxLength(255),
                    Forms\Components\Textarea::make('description_en')->label('Description (EN)')->rows(2)->columnSpanFull(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Item galeri')
                ->schema([
                    Forms\Components\Repeater::make('items')
                        ->relationship()
                        ->schema([
                            Forms\Components\FileUpload::make('path')
                                ->label('Foto')
                                ->image()
                                ->disk('public')
                                ->directory('galleries'),
                            Forms\Components\TextInput::make('video_url')
                                ->label('URL video (YouTube)')
                                ->url()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('caption')
                                ->label('Keterangan')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('sort_order')
                                ->label('Urutan')
                                ->numeric()
                                ->default(0),
                        ])
                        ->columns(2)
                        ->defaultItems(0)
                        ->addActionLabel('Tambah item'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover_path')->label('Sampul')->disk('public')->height(40)->width(60),
                Tables\Columns\TextColumn::make('title')->label('Album')->searchable()->sortable()->limit(45),
                Tables\Columns\TextColumn::make('type')->label('Tipe')->badge(),
                Tables\Columns\TextColumn::make('items_count')->counts('items')->label('Item')->badge(),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->date('d M Y'),
            ])
            ->defaultSort('created_at', 'desc')
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
            'index' => Pages\ListGalleries::route('/'),
            'create' => Pages\CreateGallery::route('/create'),
            'edit' => Pages\EditGallery::route('/{record}/edit'),
        ];
    }
}
