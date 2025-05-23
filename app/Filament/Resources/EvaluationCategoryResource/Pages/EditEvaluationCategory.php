<?php

namespace App\Filament\Resources\EvaluationCategoryResource\Pages;

use App\Filament\Resources\EvaluationCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEvaluationCategory extends EditRecord
{
    protected static string $resource = EvaluationCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
