<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab; // Import Tab
use Illuminate\Database\Eloquent\Builder; // Import Builder
use Carbon\Carbon;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
      // Tambahkan metode getTabs()
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua')
                ->badge(static::getResource()::getModel()::count()), // Menampilkan jumlah total
            'this_week' => Tab::make('Minggu Ini')
                ->modifyQueryUsing(function (Builder $query) {
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                })
                ->badge(static::getResource()::getModel()::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count()),
            'this_month' => Tab::make('Bulan Ini')
                ->modifyQueryUsing(function (Builder $query) {
                    $query->whereMonth('created_at', Carbon::now()->month)
                          ->whereYear('created_at', Carbon::now()->year);
                })
                ->badge(static::getResource()::getModel()::whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->count()),
            'this_year' => Tab::make('Tahun Ini')
                ->modifyQueryUsing(function (Builder $query) {
                    $query->whereYear('created_at', Carbon::now()->year);
                })
                ->badge(static::getResource()::getModel()::whereYear('created_at', Carbon::now()->year)->count()),
        ];
    }
}
