<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\User; // Import model User

class CustomDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home'; // Icon yang lebih umum untuk dashboard
    protected static string $view = 'filament.pages.custom-dashboard';

    protected static ?string $navigationLabel = 'Dashboard'; // Cukup 'Dashboard'
    protected static ?string $title = 'Ringkasan Sistem'; // Judul yang lebih deskriptif
    protected static ?string $slug = 'dashboard'; // Mengganti slug dashboard default

    public function getViewData(): array
    {
        return [
            'totalUsers' => User::count(),
            'latestFeedbacks' => \App\Models\Feedback::latest()->take(5)->get(),
            // Anda bisa menambahkan data lain yang perlu diakses langsung di Blade di sini
            // Misalnya: 'latestFeedbacks' => \App\Models\Feedback::latest()->take(5)->get(),
        ];
    }
}