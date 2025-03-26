<?php

namespace App\Filament\Resources;

use App\Enums\ProductStatusEnum as EnumsProductStatusEnum;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\Pages\EditProduct;
use App\Filament\Resources\ProductResource\Pages\ProductImages;
use App\Filament\Resources\ProductResource\Pages\ProductVariationsTypes;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    // protected static ?string $navigationIcon = 'heroicon-s-queue-list';
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->schema([
                        TextInput::make('title')
                            ->live(onBlur: true)
                            ->required()
                            ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                $set('slung', Str::slug($state)); // ✅ Fixed "slung" to "slug"
                            }),
                        TextInput::make('slung')
                            ->required(),
                        Select::make('department_id')
                            ->relationship('department', 'name')
                            ->label('Department')
                            ->preload()
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                $set('category_id', null);
                            }),
                        Select::make('category_id')
                            ->relationship(
                                name: 'category',
                                titleAttribute: 'name',
                                modifyQueryUsing: function (Builder $query, callable $get) {
                                    $departmentId = $get('department_id'); // ✅ Correct way to get state
                                    if ($departmentId) {
                                        $query->where('department_id', $departmentId);
                                    }
                                }
                            )
                            ->label('Category')
                            ->preload()
                            ->searchable(),
                        // ->required(),
                    ]),
                RichEditor::make('description')
                    ->required()
                    ->toolbarButtons([
                        'blockquote',
                        'bold',
                        'bulletList',
                        'h2',
                        'h3',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'underline',
                        'undo',
                        'table',
                    ])
                    ->columnSpan(2),
                TextInput::make('price')
                    ->numeric()
                    ->required(),
                TextInput::make('quantity')
                    ->integer(),
                Select::make('status')
                    ->options(EnumsProductStatusEnum::labels()) // ✅ Ensure labels() method exists
                    ->default(EnumsProductStatusEnum::Draft->value)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('images')
                    ->conversion('thumb')
                    ->limit(1)
                    ->label('Image')
                    ->collection('images'),
                TextColumn::make('title')
                    ->words(10)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->colors(EnumsProductStatusEnum::colors()),
                TextColumn::make('department.name'),
                TextColumn::make('category.name'),
                TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(EnumsProductStatusEnum::labels()),
                SelectFilter::make('department_id')
                    ->relationship('department', 'name'),
                // ->label('Department')
                // ->optionsLabel('All')
                // ->searchable()
                // ->default(null),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
            'images' => Pages\ProductImages::route('/{record}/images'),
            'variation-types' => Pages\ProductVariationsTypes::route('/{record}/variation-types'),
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->getNavigationItems([
            EditProduct::class,
            ProductImages::class,
            ProductVariationsTypes::class,
        ]);
    }
}
