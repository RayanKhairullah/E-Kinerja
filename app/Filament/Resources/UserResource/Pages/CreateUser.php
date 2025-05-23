<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Spatie\Permission\Models\Role;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Assign default role 'teacher' if no roles were selected
        if (empty($data['roles'])) {
            $teacherRole = Role::where('name', 'teacher')->first();
            if ($teacherRole) {
                // Filament's relationship field will handle attaching roles
                // so we just ensure the 'roles' key is present with the ID
                $data['roles'] = [$teacherRole->id];
            }
        }
        return $data;
    }
}
