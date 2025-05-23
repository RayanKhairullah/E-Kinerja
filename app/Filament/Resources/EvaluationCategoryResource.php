<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EvaluationCategoryResource\Pages;
use App\Filament\Resources\EvaluationCategoryResource\RelationManagers;
use App\Models\EvaluationCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope; // Tetap import, tapi tidak digunakan jika fitur dinonaktifkan
use Filament\Tables\Filters\DatePickerFilter; // Ditambahkan
use Filament\Tables\Filters\TrashedFilter; // Ditambahkan, jika menggunakan SoftDeletes
use Filament\Tables\Filters\SelectFilter; // Ditambahkan
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;

class EvaluationCategoryResource extends Resource
{
    protected static ?string $model = EvaluationCategory::class;

    protected static ?string $modelLabel = 'Kategori Evaluasi';
    protected static ?string $pluralModelLabel = 'Kategori Evaluasi';
    protected static ?string $navigationGroup = 'Manajemen Evaluasi';
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?int $navigationSort = 4;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Kategori'),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->label('Deskripsi'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Dibuat Pada'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Diperbarui Pada'),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('Dari Tanggal'),
                        DatePicker::make('created_until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    })
                    ->label('Tanggal Dibuat'),
                // Tables\Filters\TrashedFilter::make(), // Aktifkan HANYA JIKA model App\Models\EvaluationCategory menggunakan Illuminate\Database\Eloquent\SoftDeletes;
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    // Tables\Actions\ForceDeleteBulkAction::make(), // Aktifkan HANYA JIKA SoftDeletes aktif
                    // Tables\Actions\RestoreBulkAction::make(), // Aktifkan HANYA JIKA SoftDeletes aktif
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvaluationCategories::route('/'),
            'create' => Pages\CreateEvaluationCategory::route('/create'),
            'view' => Pages\ViewEvaluationCategory::route('/{record}'),
            'edit' => Pages\EditEvaluationCategory::route('/{record}/edit'),
        ];
    }

    // Komentar fungsi ini HANYA JIKA model App\Models\EvaluationCategory TIDAK menggunakan SoftDeletes.
    // Jika model Anda menggunakan SoftDeletes, Anda dapat mengaktifkan kembali fungsi ini.
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}