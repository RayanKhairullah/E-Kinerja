<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EvaluationValidationResource\Pages;
use App\Filament\Resources\EvaluationValidationResource\RelationManagers;
use App\Models\EvaluationValidation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ExportAction;
use App\Filament\Exports\EvaluationValidationExporter;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Filters\SelectFilter; // Ditambahkan
use Filament\Tables\Filters\DatePickerFilter; // Ditambahkan
use App\Models\User; // Ditambahkan, untuk filter validator
use App\Models\EvaluationCategory; // Ditambahkan, untuk filter category
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;

class EvaluationValidationResource extends Resource
{
    protected static ?string $model = EvaluationValidation::class;

    protected static ?string $modelLabel = 'Validasi Evaluasi';
    protected static ?string $pluralModelLabel = 'Validasi Evaluasi';
    protected static ?string $navigationGroup = 'Manajemen Evaluasi';
    protected static ?string $navigationIcon = 'heroicon-o-check-badge';
    protected static ?int $navigationSort = 3;
    protected static ?string $recordTitleAttribute = 'status';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('evaluation_id')
                    ->relationship('evaluation', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "Evaluation for " . ($record->evaluatedUser->name ?? 'N/A') . " - " . ($record->category->name ?? 'N/A'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Evaluation'),
                Forms\Components\Select::make('validator_id')
                    ->options(function () {
                        return User::whereDoesntHave('roles', function ($query) {
                            $query->where('name', 'admin');
                        })->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Validator'),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('evaluation.evaluatedUser.name')
                    ->label('Evaluated User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('evaluation.category.name')
                    ->label('Evaluation Category')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('validator.name')
                    ->label('Validator')
                    ->searchable()
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
                SelectFilter::make('evaluated_user')
                    ->label('Evaluated User')
                    ->relationship('evaluation.evaluatedUser', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('evaluation_category')
                    ->label('Evaluation Category')
                    ->relationship('evaluation.category', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('validator_id')
                    ->label('Validator')
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->options([
                        // Sesuaikan dengan status yang Anda miliki
                        'pending' => 'Pending',
                        'validated' => 'Tervalidasi',
                        'rejected' => 'Ditolak',
                    ])
                    ->label('Status Validasi'),
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
                    ->label('Tanggal Validasi'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()->exporter(EvaluationValidationExporter::class),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(EvaluationValidationExporter::class)
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
            'index' => Pages\ListEvaluationValidations::route('/'),
            'create' => Pages\CreateEvaluationValidation::route('/create'),
            'view' => Pages\ViewEvaluationValidation::route('/{record}'),
            'edit' => Pages\EditEvaluationValidation::route('/{record}/edit'),
        ];
    }
}