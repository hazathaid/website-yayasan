<?php

namespace App\Filament\Widgets;

use App\Models\Contact;
use App\Models\Donation;
use App\Models\Post;
use App\Models\Program;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Program Aktif', Program::where('is_published', true)->where('status', 'active')->count())
                ->description('Program berjalan')
                ->color('success'),
            Stat::make('Donasi Terverifikasi', rupiah(Donation::verified()->sum('amount')))
                ->description(Donation::verified()->count().' transaksi')
                ->color('info'),
            Stat::make('Donasi Menunggu', Donation::where('status', 'pending')->count())
                ->description('Perlu diverifikasi')
                ->color('warning'),
            Stat::make('Pesan Belum Dibaca', Contact::where('is_read', false)->count())
                ->description(Post::published()->count().' berita terbit')
                ->color('gray'),
        ];
    }
}
