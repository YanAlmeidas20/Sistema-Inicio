<?php

namespace App\Filament\Resources;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\BooleanColumn;
use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $label = 'Funcionários';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('name')
                ->maxLength(255)
                ->label('Nome')
                ->required(),
            
                DatePicker::make('birth_date')
                ->label('Data de Nascimento')
                ->required(),
            
                TextInput::make('cpf')
                ->label('CPF')
                ->required()
                ->unique(),
            
                TextInput::make('role')
                ->maxLength(255)
                ->label('Função')
                ->required(),
            
                Select::make('type')
            ->label('Tipo de Usuário')
            ->options([
                'admin' => 'Admin',
                'common' => 'Comum',
                'register' => 'Registrado',
            ])
            ->reactive() 
            ->required(),

        TextInput::make('registered_info')
            ->label('Informações do Registro')
            ->placeholder('Ex: CRM: 1234-BA') // Profissionais com registro em conselho
            ->required()
            ->hidden(fn (callable $get) => $get('type') !== 'register'),
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('name')
                ->sortable()
                ->searchable(),

            TextColumn::make('role')
                ->sortable(),

            BadgeColumn::make('status')
                ->badge(fn ($state) => match ($state) {
                    'active' => 'success',
                    'inactive' => 'danger',
                    'pending' => 'warning',
                    default => 'secondary', 
                }),
        ])
        ->filters([
           
        ]);
}

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
    public static function getNavigationGroup(): ?string
{
    return 'Administrativo'; 
}

public static function getNavigationLabel(): string
{
    return 'Funcionários'; // Nome do item no menu
}

public static function getNavigationIcon(): string
{
    return 'heroicon-o-user'; 
}
}