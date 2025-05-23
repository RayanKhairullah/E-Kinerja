<x-filament-panels::page>
    {{-- Mengambil nama pengguna yang login --}}
    @php
        $loggedInUserName = auth()->user()->name ?? 'Pengguna';
    @endphp

    <x-filament::section>
        <x-slot name="heading">
            <h2 class="text-xl font-bold">Selamat Datang, {{ $loggedInUserName }}!</h2>
        </x-slot>

        {{-- Bagian Statistik / Ringkasan --}}
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Kartu Statistik Total Pengguna --}}
            <x-filament::card class="col-span-1">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-primary-100 rounded-full dark:bg-primary-500/20">
                        <x-heroicon-o-users class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengguna</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalUsers }}</p>
                    </div>
                </div>
            </x-filament::card>

            {{-- Contoh Kartu Statistik Lain (Anda bisa tambahkan dari data model lain) --}}
            <x-filament::card class="col-span-1">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-emerald-100 rounded-full dark:bg-emerald-500/20">
                        <x-heroicon-o-clipboard-document-check class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Evaluasi</p>
                        {{-- Contoh placeholder, ganti dengan data aktual --}}
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ \App\Models\Evaluation::count() }}
                        </p>
                    </div>
                </div>
            </x-filament::card>

            <x-filament::card class="col-span-1">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-red-100 rounded-full dark:bg-red-500/20">
                        <x-heroicon-o-chat-bubble-left-right class="w-6 h-6 text-red-600 dark:text-red-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Feedback</p>
                        {{-- Contoh placeholder, ganti dengan data aktual --}}
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ \App\Models\Feedback::count() }}
                        </p>
                    </div>
                </div>
            </x-filament::card>
        </div>

        {{-- Bagian Aksi Cepat / Informasi Penting --}}
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <x-filament::card class="col-span-1">
                <x-slot name="heading">
                    Aksi Cepat
                </x-slot>
                <div class="space-y-4">
                    <x-filament::button
                        href="{{ \App\Filament\Resources\EvaluationResource::getUrl('create') }}"
                        icon="heroicon-o-plus"
                        tag="a"
                        full
                    >
                        Buat Evaluasi Baru
                    </x-filament::button>
                    <x-filament::button
                        href="{{ \App\Filament\Resources\FeedbackResource::getUrl('create') }}"
                        icon="heroicon-o-chat-bubble-bottom-center-text"
                        tag="a"
                        color="secondary"
                        full
                    >
                        Berikan Feedback
                    </x-filament::button>
                    {{-- Tambahkan tombol aksi cepat lainnya di sini --}}
                </div>
            </x-filament::card>

            <x-filament::card class="col-span-1">
                <x-slot name="heading">
                    Informasi Terkini
                </x-slot>
                <div class="space-y-4">
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        daftar evaluasi/feedback terbaru, atau pengumuman di sini.
                    </p>
                    {{-- Contoh: Daftar Feedback Terbaru --}}
                    
                    @if (!empty($latestFeedbacks))
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($latestFeedbacks as $feedback)
                                <li class="py-2">
                                    <p class="font-semibold">{{ $feedback->givenBy->name ?? 'N/A' }} untuk {{ $feedback->evaluation->evaluatedUser->name ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 truncate">{{ $feedback->content }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-600 dark:text-gray-400">Belum ada feedback terbaru.</p>
                    @endif
                </div>
            </x-filament::card>
        </div>

        {{-- Bagian untuk Grafik atau Analisis Lebih Lanjut (Opsional) --}}
        {{-- <div class="mt-8">
            <x-filament::card>
                <x-slot name="heading">
                    Analisis Tren Evaluasi
                </x-slot>
                <p class="text-gray-700 dark:text-gray-300">
                    Anda bisa mengintegrasikan grafik di sini menggunakan Livewire Charting
                    atau library grafik lainnya.
                </p>
                {{-- Contoh placeholder untuk grafik --}}
                {{-- <div class="h-64 bg-gray-100 dark:bg-gray-800 rounded-md flex items-center justify-center text-gray-500 mt-4">
                    Area Placeholder Grafik
                </div>
            </x-filament::card>
        </div> --}}

    </x-filament::section>
</x-filament-panels::page>