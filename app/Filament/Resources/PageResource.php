<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?string $modelLabel = 'Halaman';

    protected static ?string $pluralModelLabel = 'Halaman';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Halaman')
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
                    Forms\Components\RichEditor::make('content')
                        ->label('Konten')
                        ->required()
                        ->columnSpanFull(),
                    Forms\Components\Toggle::make('is_published')->label('Publikasikan')->default(true),
                ])
                ->columns(2),

            Forms\Components\Section::make('SEO')
                ->schema([
                    Forms\Components\TextInput::make('meta_title')->label('Meta title')->maxLength(255),
                    Forms\Components\Textarea::make('meta_description')->label('Meta description')->rows(2)->maxLength(500),
                ])
                ->columns(2)
                ->collapsed(),

            Forms\Components\Section::make('Terjemahan (English)')
                ->schema([
                    Forms\Components\TextInput::make('title_en')->label('Title (EN)')->maxLength(255),
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
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->toggleable(),
                Tables\Columns\IconColumn::make('is_published')->label('Publik')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->label('Diperbarui')->dateTime('d M Y'),
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
