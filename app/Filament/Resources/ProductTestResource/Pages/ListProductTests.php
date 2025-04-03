<?php

namespace App\Filament\Resources\ProductTestResource\Pages;

use App\Filament\Resources\ProductTestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductTests extends ListRecords
{
    protected static string $resource = ProductTestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
