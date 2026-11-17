<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Pengaturan Situs';

    protected static ?string $title = 'Pengaturan Situs';

    protected static ?int $navigationSort = 9;

    protected static string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $keys = [
            'site_name', 'logo_path', 'tagline', 'description', 'address', 'phone',
            'email', 'whatsapp', 'facebook', 'instagram', 'youtube',
            'bank_name', 'bank_account', 'bank_holder', 'meta_description', 'maps_embed',
            'tagline_en', 'description_en', 'meta_description_en',
        ];

        $data = [];
        foreach ($keys as $key) {
            $data[$key] = Setting::get($key);
        }

        $this->form->fill($data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Identitas Situs')
                    ->schema([
                        TextInput::make('site_name')->label('Nama yayasan')->required()->maxLength(150),
                        FileUpload::make('logo_path')
                            ->label('Logo')
                            ->image()
                            ->disk('public')
                            ->directory('branding')
                            ->maxSize(2048),
                        TextInput::make('tagline')->label('Tagline')->maxLength(150),
                        Textarea::make('description')->label('Deskripsi singkat')->rows(3),
                        TextInput::make('meta_description')->label('Meta description')->maxLength(500),
                    ])
                    ->columns(2),
                Section::make('Terjemahan (English)')
                    ->schema([
                        TextInput::make('tagline_en')->label('Tagline (EN)')->maxLength(150),
                        Textarea::make('description_en')->label('Short description (EN)')->rows(3),
                        TextInput::make('meta_description_en')->label('Meta description (EN)')->maxLength(500),
                    ])
                    ->columns(2)
                    ->collapsed(),
                Section::make('Kontak')
                    ->schema([
                        Textarea::make('address')->label('Alamat')->rows(2),
                        TextInput::make('phone')->label('Telepon')->maxLength(50),
                        TextInput::make('email')->label('Email')->email()->maxLength(150),
                        TextInput::make('whatsapp')->label('Nomor WhatsApp')->helperText('Format: 628xxxxxxxxxx')->maxLength(30),
                        TextInput::make('maps_embed')->label('URL embed Google Maps')->maxLength(500)->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Media Sosial')
                    ->schema([
                        TextInput::make('facebook')->label('Facebook')->url(),
                        TextInput::make('instagram')->label('Instagram')->url(),
                        TextInput::make('youtube')->label('YouTube')->url(),
                    ])
                    ->columns(3),
                Section::make('Rekening Donasi')
                    ->schema([
                        TextInput::make('bank_name')->label('Nama bank'),
                        TextInput::make('bank_account')->label('Nomor rekening'),
                        TextInput::make('bank_holder')->label('Atas nama'),
                    ])
                    ->columns(3),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::put($key, $value);
        }

        Notification::make()
            ->title('Pengaturan berhasil disimpan')
            ->success()
            ->send();
    }
}
