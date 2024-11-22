<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerResource\Pages\ListPartners;
use App\Filament\Resources\PartnerResource\Pages\CreatePartner;
use App\Filament\Resources\PartnerResource\Pages\EditPartner;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class PartnerResource extends Resource
{
  
    protected static ?string $model = \App\Models\Partner::class;
    protected static ?string $label = 'Parceiros';
    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nome/Razão Social')
                    ->required()
                    ->maxLength(255),

                TextInput::make('fantasy_name')
                    ->label('Nome Fantasia')
                    ->nullable()
                    ->maxLength(255),

                TextInput::make('cpf_cnpj')
                    ->label('CPF ou CNPJ')
                    ->required()
                    ->maxLength(18)
                    ->mask('###.###.###/####-##'),

                Select::make('category')
                ->label('Categoria')
                ->options([
                    'Fornecedor' => 'Fornecedor',
                    'Outros' => 'Outros',
                ])
                ->required()
                ->reactive()
                ->afterStateUpdated(function (callable $set, $state) {
                    if ($state !== 'Outros') {
                        $set('other_category', null);
                    }
                }),

            TextInput::make('other_category')
                ->label('Especificar Parceria')
                ->nullable()
                ->visible(fn ($get) => $get('category') === 'Outros') // Só será visível quando "Outros" for selecionado
                ->required()
                ->maxLength(255),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('fantasy_name')
                    ->sortable(),

                TextColumn::make('cpf_cnpj')
                    ->sortable(),

                TextColumn::make('category')
                    ->sortable(),
            ])
            ->filters([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPartners::route('/'),
            'create' => CreatePartner::route('/create'),
            'edit' => EditPartner::route('/{record}/edit'),
        ];
    }
    public static function getNavigationGroup(): ?string
    {
        return 'Administrativo'; 
    }
    
    public static function getNavigationLabel(): string
    {
        return 'Parceiros'; // Nome do item no menu
    }
    
    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-face-smile'; 
    }
}
