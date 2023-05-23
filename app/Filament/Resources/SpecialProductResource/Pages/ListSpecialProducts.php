<?php

namespace App\Filament\Resources\SpecialProductResource\Pages;

use App\Filament\Resources\SpecialProductResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListSpecialProducts extends ListRecords
{
    protected static string $resource = SpecialProductResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->where('type', 'special_product');
    }
}
