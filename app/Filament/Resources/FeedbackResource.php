<?php

namespace App\Filament\Resources;

use App\Filament\Exports\FeedbackExporter;
use App\Filament\Resources\FeedbackResource\Pages;
use App\Filament\Resources\FeedbackResource\RelationManagers;
use App\Models\Feedback;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Filters\SelectFilter; // Ditambahkan
use Filament\Tables\Filters\DatePickerFilter; // Ditambahkan
use App\Models\User; // Ditambahkan, untuk filter givenBy
use App\Models\EvaluationCategory; // Ditambahkan, untuk filter category
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;

class FeedbackResource extends Resource
{
    protected static ?string $model = Feedback::class;

    protected static ?string $modelLabel = 'Feedback';
    protected static ?string $pluralModelLabel = 'Feedback';
    protected static ?string $navigationGroup = 'Manajemen Evaluasi';
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'content';

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
                Forms\Components\Select::make('given_by_id')
                    ->options(function () {
                        return User::whereDoesntHave('roles', function ($query) {
                            $query->where('name', 'admin');
                        })->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Given By'),
                Forms\Components\Textarea::make('content')
                    ->required()
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
                Tables\Columns\TextColumn::make('givenBy.name')
                    ->label('Given By')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('content')
                    ->label('Feedback Content')
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
                SelectFilter::make('given_by_id')
                    ->label('Given By User')
                    ->options(User::all()->pluck('name', 'id')) // Atau gunakan relationship jika ada langsung ke User
                    ->searchable()
                    ->preload(),
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
                    ->label('Tanggal Feedback'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()->exporter(FeedbackExporter::class),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(FeedbackExporter::class)
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
            'index' => Pages\ListFeedback::route('/'),
            'create' => Pages\CreateFeedback::route('/create'),
            'view' => Pages\ViewFeedback::route('/{record}'),
            'edit' => Pages\EditFeedback::route('/{record}/edit'),
        ];
    }
}