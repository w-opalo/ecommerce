<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Enums\Enums\ProductVariationTypesEnum;
use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductVariations extends EditRecord
{
    protected static string $resource = ProductResource::class;

    // protected static string $resource = ProductResource::class;

    protected static ?string $title = 'Variation';
    protected static ?string $navigationIcon = 'heroicon-o-collection';


    public function form(Form $form): Form
    {
        $types = $this->record->variationTypes;
        $fields = [];
        foreach ($types as $type) {
            $fields[] = TextInput::make('variationTypes_' . ($type->id) . '.id')
                ->hidden();
            $fields[] = TextInput::make('variationTypes_' . ($type->id) . '.name')
                ->label($types->name);
        }
        return $form
            ->schema([
                Repeater::make('variations')
                    ->label(false)
                    ->collapsible()
                    ->addable(false)
                    ->defaultItems(1)
                    ->schema([
                        Section::make()
                            ->schema($fields)
                            ->columns(3),
                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric(),
                        TextInput::make('price')
                            ->label('Price')
                            ->numeric(),

                    ])
                    ->columns(2)
                    ->columnSpan(2)

            ]);
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
                //******   ******//
                $product['id'] = $existingEntry['id'];
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
                        'variation_type_' . ($variationType->id) => [
                            'id' => $option->id,
                            'name' => $option->name,
                            'label' => $variationType->name,
                        ],
                    ];

                    $temp[] = $newCombination;
                }
            }
            $result = $temp; //update results with new combinations
        }

        //add quantity and price ti completed combinations
        foreach ($result as $key => &$combination) {
            if (count($combination) === count($variationType)) {
                $combination['quantity'] = $defaultQuantity;
                $combination['price'] = $defaultPrice;
            }
        }

        return $result;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        //Array to initialize an array to hold formatted data
        $formattedData = [];

        //Loop through each variation to restructure it
        foreach ($data['variations'] as $option) {
            $variationtypeOptionIds = [];
            foreach ($this->record->variationtypes as $i => $variationTypes) {
                $variationtypeOptionIds[] = $option['variationTypes_' . $variationTypes->id]['id'];
            }

            $quantity = $option['quantity'];
            $price = $option['price'];

            $formattedData[] = [
                'id' => $option['id'], ///**********  *******////
                'variation_type_option_ids' => $variationtypeOptionIds,
                'quantity' => $quantity,
                'price' => $price,
            ];
        }

        $data['variations'] = $formattedData;

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $variations = $data['variations'];
        unset($data['variations']);
        $variations = collect($variations)->map(function ($variation) {
            return [
                'id' => $variation['id'],
                'variation_type_option_ids' => json_encode($variation['variation_type_option_ids']), // $variation['variation_type_option_ids'],
                'quantity' => $variation['quantity'],
                'price' => $variation['price'],
            ];
        })->toArray();
        // $record->update($data);
        // $record->variations()->delete();
        $record->variations()->upsert($variations, ['id'], ['variation_type_option_ids', 'quantity', 'price']);
        // $record->variations()->createMany($variations);
        // $record = parent::handleRecordUpdate($record, $data);

        return $record;
        // return parent::handleRecordUpdate($record, $data);
    }
}


// protected function mutateFormDataBeforeSave(array $data): array
// {
//     // Modify the data if needed
//     $data['some_field'] = strtoupper($data['some_field'] ?? '');

//     return $data;
// }
