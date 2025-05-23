<?php

namespace App\Filament\Resources\EvaluationValidationResource\Pages;

use App\Filament\Resources\EvaluationValidationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEvaluationValidation extends EditRecord
{
    protected static string $resource = EvaluationValidationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
