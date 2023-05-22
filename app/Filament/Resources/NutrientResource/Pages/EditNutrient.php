<?php

namespace App\Filament\Resources\NutrientResource\Pages;

use App\Filament\Resources\NutrientResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNutrient extends EditRecord
{
    protected static string $resource = NutrientResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
