<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['created_by'] = Auth::id(); // Ensures user is logged in
        $data['updated_by'] = Auth::id();

        return $data;
        // $data['created_by'] = auth()->id();
        // $data['updated_by'] = auth()->id();

        // return $data;
    }
}
