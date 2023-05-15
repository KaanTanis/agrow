<?php

namespace App\Filament\Resources\HomeFieldResource\Pages;

use App\Filament\Resources\HomeFieldResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHomeField extends EditRecord
{
    protected static string $resource = HomeFieldResource::class;

    protected function getActions(): array
    {
        return [
//            Actions\DeleteAction::make(),
        ];
    }
}
