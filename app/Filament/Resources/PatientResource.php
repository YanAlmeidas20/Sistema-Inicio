<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages\ListPatients;
use App\Filament\Resources\PatientResource\Pages\CreatePatient;
use App\Filament\Resources\PatientResource\Pages\EditPatient;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;

class PatientResource extends Resource
{
    
    protected static ?string $label = 'Clientes';
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $model = \App\Models\Patient::class;
    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
        ->schema([
            TextInput::make('name')
                ->label('Nome')
                ->required()
                ->maxLength(255),

            TextInput::make('address')
                ->label('Endereço')
                ->required()
                ->maxLength(255),

            TextInput::make('email')
                ->label('Email')
                ->required()
                ->email()
                ->maxLength(255),

            TextInput::make('phone')
                ->label('Telefone')
                ->required()
                ->required()
                ->placeholder('(99) 99999-9999'),

            TextInput::make('birth_date')
                ->label('Data de Nascimento')
                ->required()
                ->maxLength(20),

            TextInput::make('cpf')
                ->label('CPF')
                ->required()
                ->maxLength(14),

            TextInput::make('rg')
                ->label('RG')
                ->nullable()
                ->required()            
                ->maxLength(20),

            TextInput::make('cnh')
                ->label('CNH')
                ->nullable()
                ->maxLength(20),

            TextInput::make('father_name')
                ->label('Nome do Pai')
                ->nullable()
                ->helperText('Deixe em branco se não registrado')
                ->maxLength(255),

            TextInput::make('mother_name')
                ->label('Nome da Mae')
                ->nullable()
                ->required()
                ->maxLength(255),

            Textarea::make('allergies')
                ->label('Alergias')
                ->nullable()
                ->maxLength(500),

            Textarea::make('medical_history')
                ->label('Histórico Médico')
                ->nullable()
                ->maxLength(500),

            TextInput::make('civil_status')
                ->label('Status Civil')
                ->nullable()
                ->required()
                ->maxLength(50),

            TextInput::make('nationality')
                ->label('Nacionalidade')
                ->nullable()
                ->required()
                ->maxLength(100),

            TextInput::make('birthplace')
                ->label('Naturalidade')
                ->nullable()
                ->required()
                ->maxLength(100),

            Forms\Components\Checkbox::make('has_debts')
                ->label('Possui Debitos')
                ->reactive()
                ->default(false),

                TextInput::make('debt_description')
                ->label('Qual Debito?') 
                ->nullable()
                ->visible(fn ($get) => $get('has_debts') === true) //Interação com possui debitos
                ->maxLength(255)
                ->required()
                ->placeholder('Descreva o débito'),

            Forms\Components\Checkbox::make('has_health_insurance')
                ->label('Possui Convênio')
                ->reactive()
                ->default(false),

                TextInput::make('dehealth_description')
                ->label('Qual Convênio?') 
                ->nullable()
                ->visible(fn ($get) => $get('has_health_insurance') === true) // Interação com possui convênio
                ->maxLength(255)
                ->required()
                ->placeholder('Digite o Convênio'),
        ]);
}


    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('birthdate')
                    ->label('Data de Nascimento')
                    ->sortable()
                    ->date(),

                TextColumn::make('cpf')
                    ->sortable(),

                TextColumn::make('email')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('Telefone')
                    ->sortable(),
            ])
            ->filters([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPatients::route('/'),
            'create' => CreatePatient::route('/create'),
            'edit' => EditPatient::route('/{record}/edit'),
        ];
    }
    public static function getNavigationGroup(): ?string
{
    return 'Administrativo';
}

public static function getNavigationLabel(): string
{
    return 'Clientes'; // Nome do item no menu
}

public static function getNavigationIcon(): string
{
    return 'heroicon-o-user-group'; 
}
}