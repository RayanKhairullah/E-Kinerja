<?php

namespace App\Filament\Resources\EvaluationValidationResource\Pages;

use App\Filament\Resources\EvaluationValidationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEvaluationValidation extends ViewRecord
{
    protected static string $resource = EvaluationValidationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
