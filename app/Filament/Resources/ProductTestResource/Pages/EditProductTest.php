<?php

namespace App\Filament\Resources\ProductTestResource\Pages;

use App\Filament\Resources\ProductTestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductTest extends EditRecord
{
    protected static string $resource = ProductTestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
