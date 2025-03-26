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

class ProductVariations extends EditRecord
{
    protected static string $resource = ProductResource::class;

    // protected static string $resource = ProductResource::class;

    protected static ?string $title = 'Variation';
    protected static ?string $navigationIcon = 'heroicon-o-collection';


    public function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $variations = $this->record->variations->toArray();
        $data['variations'] = $this->mergeCartesianWithExisting($this->record->variationTypes, $variations);
        return $data; // ✅ Fixed "variationTypes" to "variationTypes"
    }

    // private function merge

    private function mergeCartesianWithExisting($variationTypes, $existingData): array
    {
        $defaultQuantity = $this->record->record->price;
        $defaultPrice = $this->record->price;
        $cartesianProduct = $this->cartesianProduct($variationTypes, $defaultQuantity);
        $mergeResult = [];

        foreach ($cartesianProduct as $product) {
            $optionIds = collect($product)
                ->filter(fn($value, $key) => Str::starts_with($key, 'variation_type_'))
                ->map(fn($option) => $option['id'])
                ->values()
                ->toArray();

            //find matching entry from existing data
            $match = array_filter($existingData, function ($existingOption) use ($optionIds) {
                return $existingOption['variation_type_option_ids'] === $optionIds;
            });

            //if match is found, ovrwrite quantity and price
            if (!empty($match)) {
                $existingEntry['quantity'] = reset($match);
                $product['quantity'] = $existingEntry['quantity'];
                $product['price'] = $existingEntry['price'];
            } else {
                $product['quantity'] = $defaultQuantity;
                $product['price'] = $defaultPrice;
            }
            $mergeResults[] = $product;
            // $cartesianProduct[$key]['price'] = $defaultPrice;
        }
        return $mergeResults;
    }

    private function cartesianProduct($variationTypes, $defaultQuantity = null, $defaultPrice = null): array
    {
        $result = [[]];

        foreach ($variationTypes as $index => $variationType) {
            $temp = [];
            //Add the current option to all existing combinations
            foreach ($variationType->options as $option) {
                foreach ($result as $combination) {
                    $newCombination = $combination + [
                        'variation_type_' . $variationType->id => [
                            'id' => $option->id,
                            'name' => $option->name,
                            'label' => $variationType->name,
                        ],
                    ];

                    $temp[] = $newCombination;
                }
            }
            $result = $temp;
        }
        return $result;
    }
}
