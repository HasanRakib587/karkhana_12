<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressRelationManager extends RelationManager
{
    protected static string $relationship = 'address';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('first_name')
                ->required()
                ->maxLength(255),

                TextInput::make('last_name')
                ->required()
                ->maxLength(255),

                TextInput::make('phone')
                ->required()
                ->maxLength(20)
                ->tel(),

                TextInput::make('city')
                ->required()
                ->maxLength(255),

                TextInput::make('division')
                ->required()
                ->maxLength(255),

                TextInput::make('district')
                ->required()
                ->maxLength(255),

                TextInput::make('zip_code')
                ->required()
                ->numeric()
                ->maxLength(10),

                Textarea::make('street_address')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('street_address')
            ->columns([
                TextColumn::make('full_name')
                ->label('Full Name'),

                TextColumn::make('phone')
                ->label('Phone Number'),

                TextColumn::make('district')
                ->label('District'),

                TextColumn::make('city')
                ->label('City'),
                
                TextColumn::make('street_address')
                ->label('Street Address'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
