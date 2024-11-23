<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountReceivableResource\Pages;
use App\Models\AccountReceivable;
use App\Models\Partner; 
use Filament\Forms; 
use Filament\Forms\Form; 
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select; 
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter; 

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
                
                Select::make('partner_id') 
                    ->label('Devedor')
                    ->options(Partner::all()->pluck('name', 'id')) 
                    ->searchable()
                    ->required() 
                    ->placeholder('Selecione um devedor'),

                    FileUpload::make('receipt_file')
                ->label('Comprovante de Pagamento')
                ->directory('receipts') // Diretório onde o arquivo será salvo
                ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png']) // Tipos aceitos
                ->helperText('Envie um arquivo PDF ou imagem do comprovante.')
                ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('description')
                    ->label('Descrição')
                    ->searchable(),
                
                TextColumn::make('amount')
                    ->label('Valor')
                    ->money('BRL') 
                    ->sortable(),
                TextColumn::make('due_date')
                    ->label('Data de Vencimento')
                    ->date()
                    ->sortable(),
                BooleanColumn::make('is_paid')
                    ->label('Pago'),

                IconColumn::make('receipt_file')
                    ->label('Comprovante')
                    ->boolean() // Exibe um ícone baseado na presença do valor
                    ->toggleable(isToggledHiddenByDefault: true),

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
                    ->label('Devedor')
                    ->options(Partner::all()->pluck('name', 'id')->toArray()) 
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
            //
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
