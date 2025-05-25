<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EvaluationResource\Pages;
use App\Filament\Resources\EvaluationResource\RelationManagers;
use App\Models\Evaluation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ExportAction;
use App\Filament\Exports\EvaluationExporter;
use Filament\Actions\Exports\Exporter;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Filters\SelectFilter; // Ditambahkan
use Filament\Tables\Filters\DatePickerFilter; // Ditambahkan
use App\Models\User; // Ditambahkan, untuk filter evaluatedBy, evaluatedUser
use App\Models\EvaluationCategory; // Ditambahkan, untuk filter category
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Facades\Auth;

class EvaluationResource extends Resource
{
    protected static ?string $model = Evaluation::class;

    protected static ?string $modelLabel = 'Evaluasi';
    protected static ?string $pluralModelLabel = 'Evaluasi';
    protected static ?string $navigationGroup = 'Manajemen Evaluasi';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'evaluatedUser.name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('evaluated_by_id')
                    ->default(fn () => Auth::id())
                    ->dehydrated(true),
                // Select::make('evaluated_user_id')
                //     ->options(function () {
                //         return User::whereDoesntHave('roles', function ($query) {
                //             $query->where('name', 'admin');
                //         })->pluck('name', 'id');
                //     })
                //     ->searchable()
                //     ->preload()
                //     ->required()
                //     ->label('Evaluated User'),
                Select::make('evaluation_category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Evaluation Category'),
                Forms\Components\TextInput::make('jabatan')
                    ->maxLength(255)
                    ->nullable()
                    ->label('Jabatan'),
                Forms\Components\TextInput::make('score')
                    ->numeric()
                    ->placeholder('score 1 - 5')
                    ->default(null),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('status')
                    ->placeholder('pending / validated / needs_revision')
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('evaluatedBy.name')
                    ->label('Evaluated By')
                    ->searchable()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('evaluatedUser.name')
                //     ->label('Evaluated User')
                //     ->searchable()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jabatan')
                    ->label('Jabatan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('score')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(50)
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('evaluated_by_id')
                    ->label('Evaluated By')
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),
                // SelectFilter::make('evaluated_user_id')
                //     ->label('Evaluated User')
                //     ->options(User::all()->pluck('name', 'id'))
                //     ->searchable()
                //     ->preload(),
                SelectFilter::make('evaluation_category_id')
                    ->label('Kategori Evaluasi')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'validated' => 'validated',
                        'needs_revision' => 'needs revision',
                        // Tambahkan status lain jika ada
                    ])
                    ->label('Status Evaluasi'),
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
                    ->label('Tanggal Evaluasi'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()->exporter(EvaluationExporter::class),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(EvaluationExporter::class)
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
            'index' => Pages\ListEvaluations::route('/'),
            'create' => Pages\CreateEvaluation::route('/create'),
            'view' => Pages\ViewEvaluation::route('/{record}'),
            'edit' => Pages\EditEvaluation::route('/{record}/edit'),
        ];
    }
}