<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountPayableResource\Pages;
use App\Models\AccountPayable;
use App\Models\Partner; 
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select; 
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class AccountPayableResource extends Resource
{
    protected static ?string $model = AccountPayable::class;
    protected static ?string $label = 'Contas a Pagar';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('description')
                    ->required()
                    ->label('Descrição'),

                TextInput::make('amount')
                    ->numeric()
                    ->required()
                    ->label('Valor')
                    ->helperText('Formato: 0,00'),
                
                    DatePicker::make('due_date')
                    ->required()
                    ->label('Data de Vencimento'),
                
                    Toggle::make('is_paid')
                    ->label('Pago'),
                
                    Select::make('partner_id')  // Campo para selecionar o parceiro
                    ->label('Fornecedor')
                    ->options(Partner::all()->pluck('name', 'id')) 
                    ->searchable()
                    ->placeholder('Selecione um fornecedor'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('description')
                    ->label('Descrição'),
                TextColumn::make('amount')
                    ->money('BRL')
                    ->label('Valor'),
                TextColumn::make('due_date')
                    ->date()
                    ->label('Data de Vencimento'),
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
                SelectFilter::make('partner')  
                    ->label('Fornecedor')
                    ->options(Partner::all()->pluck('name', 'id')->toArray()) // Opções para filtrar os fornecedores
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
           
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccountPayables::route('/'),
            'create' => Pages\CreateAccountPayable::route('/create'),
            'edit' => Pages\EditAccountPayable::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Financeiro';
    }
    
    public static function getNavigationLabel(): string
    {
        return 'Contas a Pagar'; // Nome do item no menu
    }
    
    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-wallet'; 
    }
}