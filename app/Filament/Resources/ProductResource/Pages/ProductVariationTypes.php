<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Enums\Enums\ProductVariationTypesEnum;
use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;

class ProductVariationsTypes extends EditRecord
{
    protected static string $resource = ProductResource::class;

    // protected static string $resource = ProductResource::class;

    protected static ?string $title = 'Variation Types';
    protected static ?string $navigationIcon = 'heroicon-o-collection';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Repeater::make('variationTypes')
                    ->label(false)
                    ->relationship()
                    ->collapsible()
                    ->defaultItems(1)
                    ->addActionLabel('Add Variation Type')
                    ->columns(2)
                    ->columnSpan(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required(),
                        Select::make('type')
                            ->options([ProductVariationTypesEnum::labels()])
                            ->required(),
                        Repeater::make('options')
                            ->relationship()
                            ->collapsible()
                            ->schema([
                                TextInput::make('name')
                                    ->columnSpan(2)
                                    ->required(),
                                SpatieMediaLibraryFileUpload::make('image')
                                    ->image()
                                    ->openable()
                                    ->multiple()
                                    ->panelLayout('grid')
                                    ->collection('images')
                                    ->reorderable()
                                    ->appendFiles()
                                    ->preserveFilenames()
                                    ->required(),
                            ])
                            ->columns(2)

                    ])
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
