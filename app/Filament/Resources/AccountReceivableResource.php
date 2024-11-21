<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountReceivableResource\Pages;
use App\Models\AccountReceivable;
use App\Models\Partner; // Usando o modelo de parceiros como devedores
use Filament\Forms; // Corrigir importação do Filament\Forms
use Filament\Forms\Form; // Corrigir importação do Form
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select; // Importar o campo Select
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter; // Filtro para devedores

class AccountReceivableResource extends Resource
{
    protected static ?string $model = AccountReceivable::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label = 'Contas a Receber';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->label('Descrição')
                    ->required()
                    ->maxLength(255),
                // Campo para o valor com formato de moeda
                Forms\Components\TextInput::make('amount')
                    ->label('Valor')
                    ->required()
                    ->numeric()
                    ->helperText('Formato: 0,00'),
                Forms\Components\DatePicker::make('due_date')
                    ->label('Data de Vencimento')
                    ->required(),
                Forms\Components\Toggle::make('is_paid')
                    ->label('Pago')
                    ->required(),
                // Campo para selecionar o devedor
                Select::make('partner_id')  // Campo para selecionar o devedor
                    ->label('Devedor')
                    ->options(Partner::all()->pluck('name', 'id')) // Lista de devedores
                    ->searchable() // Permite pesquisa
                    ->required() // Tornar obrigatório
                    ->placeholder('Selecione um devedor'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('description')
                    ->label('Descrição')
                    ->searchable(),
                // Exibindo o "Valor" como moeda
                TextColumn::make('amount')
                    ->label('Valor')
                    ->money('BRL') // Formatação como moeda
                    ->sortable(),
                TextColumn::make('due_date')
                    ->label('Data de Vencimento')
                    ->date()
                    ->sortable(),
                BooleanColumn::make('is_paid')
                    ->label('Pago'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filtro de devedor
                SelectFilter::make('partner')  // Usar o filtro de devedor
                    ->label('Devedor')
                    ->options(Partner::all()->pluck('name', 'id')->toArray()) // Opções para filtrar os devedores
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Defina relações, se necessário
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccountReceivables::route('/'),
            'create' => Pages\CreateAccountReceivable::route('/create'),
            'edit' => Pages\EditAccountReceivable::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Financeiro';
    }
    
    public static function getNavigationLabel(): string
    {
        return 'Contas a Receber'; // Nome do item no menu
    }
    
    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-wallet'; 
    }
}
