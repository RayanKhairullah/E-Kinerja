<?php

namespace App\Filament\Resources\EvaluationCategoryResource\Pages;

use App\Filament\Resources\EvaluationCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEvaluationCategory extends ViewRecord
{
    protected static string $resource = EvaluationCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
