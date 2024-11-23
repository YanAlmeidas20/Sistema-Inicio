<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryResource\Pages;
use App\Models\Inventory;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class InventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $label = 'Inventário';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label('Nome do Item'),
                
                    TextInput::make('quantity')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->label('Quantidade'),
                
                    TextInput::make('price')
                    ->numeric()
                    ->default(0.00)
                    ->required()
                    ->label('Preço Unitário'),

                    RichEditor::make('description')
                    ->label('Descrição do Item')
                    ->required(),
                
                    FileUpload::make('image')
                    ->label('Imagem do Produto')
                    ->image()
                    ->maxSize(1024) // Tamanho máximo
                    ->nullable(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nome'),
                TextColumn::make('description')->label('Descrição')->limit(50),
                TextColumn::make('quantity')->label('Quantidade'),
                TextColumn::make('price')->money('BRL')->label('Preço'),
                ImageColumn::make('image')->label('Imagem'), // Exibe a imagem na tabela
                TextColumn::make('created_at')->label('Criado em')->date(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventories::route('/'),
            'create' => Pages\CreateInventory::route('/create'),
            'edit' => Pages\EditInventory::route('/{record}/edit'),
        ];
    }
    public static function getNavigationGroup(): ?string
    {
        return 'Estoque'; 
    }
    
    public static function getNavigationLabel(): string
    {
        return 'Inventário'; // Nome do item no menu
    }
    
    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-archive-box';
}
}