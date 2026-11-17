<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?string $modelLabel = 'Berita';

    protected static ?string $pluralModelLabel = 'Berita';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Konten')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Judul')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (string $operation, ?string $state, Set $set) => $operation === 'create' ? $set('slug', Str::slug((string) $state)) : null),
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),
                    Forms\Components\Select::make('category_id')
                        ->label('Kategori')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload(),
                    Forms\Components\Select::make('tags')
                        ->label('Tag')
                        ->relationship('tags', 'name')
                        ->multiple()
                        ->searchable()
                        ->preload(),
                    Forms\Components\Textarea::make('excerpt')
                        ->label('Ringkasan')
                        ->rows(3)
                        ->maxLength(500)
                        ->columnSpanFull(),
                    Forms\Components\RichEditor::make('content')
                        ->label('Isi berita')
                        ->required()
                        ->columnSpanFull(),
                    Forms\Components\FileUpload::make('cover_path')
                        ->label('Gambar sampul')
                        ->image()
                        ->disk('public')
                        ->directory('posts')
                        ->imageEditor()
                        ->maxSize(4096)
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Publikasi')
                ->schema([
                    Forms\Components\Toggle::make('is_published')
                        ->label('Terbitkan')
                        ->default(false),
                    Forms\Components\DateTimePicker::make('published_at')
                        ->label('Tanggal terbit')
                        ->seconds(false)
                        ->default(now()),
                    Forms\Components\Hidden::make('user_id')
                        ->default(fn () => auth()->id()),
                ])
                ->columns(2),

            Forms\Components\Section::make('SEO')
                ->schema([
                    Forms\Components\TextInput::make('meta_title')
                        ->label('Meta title')
                        ->maxLength(255),
                    Forms\Components\Textarea::make('meta_description')
                        ->label('Meta description')
                        ->rows(2)
                        ->maxLength(500),
                ])
                ->columns(2)
                ->collapsed(),

            Forms\Components\Section::make('Terjemahan (English)')
                ->schema([
                    Forms\Components\TextInput::make('title_en')->label('Title (EN)')->maxLength(255),
                    Forms\Components\Textarea::make('excerpt_en')->label('Excerpt (EN)')->rows(2)->maxLength(500),
                    Forms\Components\RichEditor::make('content_en')->label('Content (EN)')->columnSpanFull(),
                    Forms\Components\TextInput::make('meta_title_en')->label('Meta title (EN)')->maxLength(255),
                    Forms\Components\Textarea::make('meta_description_en')->label('Meta description (EN)')->rows(2)->maxLength(500),
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
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable()->sortable()->limit(50),
                Tables\Columns\TextColumn::make('category.name')->label('Kategori')->badge()->sortable(),
                Tables\Columns\TextColumn::make('tags.name')->label('Tag')->badge()->separator(','),
                Tables\Columns\IconColumn::make('is_published')->label('Terbit')->boolean(),
                Tables\Columns\TextColumn::make('published_at')->label('Tanggal')->dateTime('d M Y')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('category')->relationship('category', 'name')->label('Kategori'),
                Tables\Filters\TernaryFilter::make('is_published')->label('Status terbit'),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
