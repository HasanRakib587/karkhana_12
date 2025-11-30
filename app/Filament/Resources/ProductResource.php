<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;

use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Illuminate\Support\Str;
use Filament\Forms\Set;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class ProductResource extends Resource
{
    protected static ?string $navigationGroup = 'Product Management';
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([                
                Group::make()->schema([
                    Section::make('Product Information')->schema([
                        
                        TextInput::make('name')
                        ->maxLength(255)
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn(string $operation, $state, Set $set) => 
                        $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                        TextInput::make('slug')
                        ->required()
                        ->disabled()
                        ->dehydrated(true)
                        ->maxLength(255)
                        ->unique(Product::class,'slug', ignoreRecord: true),

                        MarkdownEditor::make('description')
                        ->columnSpanFull()
                        ->fileAttachmentsDirectory('products')
                    ])->columns(2),

                    Section::make('Images')->schema([
                        FileUpload::make('images')
                        ->multiple()
                        ->directory('products')
                        ->maxFiles(5)
                        ->reorderable()
                        ->panelLayout('grid')                        
                    ])
                ])->columnSpan(2),

                Group::make()->schema([

                    Section::make('price')->schema([
                        TextInput::make('price')
                        ->required()
                        ->numeric()
                        ->prefix('BDT')
                    ]),

                    Section::make('Associations')->schema([
                        
                        Select::make('category_id')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->relationship('category', 'name'),

                        Select::make('collection_id')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->relationship('collection', 'name'),
                    ]),

                    Section::make('Status')->schema([

                        TextInput::make('stock_quantity')
                        ->required()
                        ->numeric(),

                        Toggle::make('in_stock')
                        ->required()
                        ->default(true),

                        Toggle::make('is_active')
                        ->required()
                        ->default(true),

                        Toggle::make('is_featured')                        
                        ->default(false),

                        Toggle::make('on_sale')                        
                        ->default(false),
                    ]),

                ])->columnSpan(1)

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->searchable(),

                TextColumn::make('category.name')
                ->searchable()
                ->sortable(),
                
                TextColumn::make('collection.name')
                ->searchable()
                ->sortable(),

                TextColumn::make('price')
                ->money('BDT')
                ->sortable(),

                TextColumn::make('stock_quantity')
                ->numeric()
                ->sortable(),

                IconColumn::make('is_active')
                ->boolean()
            ])
            ->filters([
                SelectFilter::make('category')
                ->relationship('category', 'name'),
                SelectFilter::make('collection')
                ->relationship('collection', 'name'),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
