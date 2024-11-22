<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput; 
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction; 
use Filament\Tables\Actions\DeleteBulkAction; 
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $label = 'Usuários';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                
                TextInput::make('cpf')
                    ->label('CPF')
                    ->required()
                    ->maxLength(14),
                
                TextInput::make('password')
                    ->label('Senha')
                    ->password()
                    ->required(fn ($context) => $context === 'create')
                    ->maxLength(255),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nome')->sortable()->searchable(),
                TextColumn::make('email')->label('Email')->sortable()->searchable(),
               # TextColumn::make('created_at')
                  #  ->label('Criado em')
                   # ->dateTime('d/m/Y H:i'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
    protected function beforeSave($record): void
{
    if ($this->form->getState('password')) {
        $record->password = bcrypt($this->form->getState('password'));
    }
}

    public static function getNavigationGroup(): ?string
    {
        return 'Administrativo';
    }

    public static function getNavigationLabel(): string
    {
        return 'Usuários';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-user-plus';
    }
}
