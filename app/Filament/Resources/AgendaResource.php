<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgendaResource\Pages;
use Filament\Forms\Components\RichEditor;
use App\Models\Agenda;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;

class AgendaResource extends Resource
{
    protected static ?string $model = Agenda::class;

    protected static ?string $label = 'Agenda';
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->maxLength(255),

                DateTimePicker::make('scheduled_at')
                    ->label('Data e Hora')
                    ->required(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pendente',
                        'confirmed' => 'Confirmado',
                        'canceled' => 'Cancelado',
                    ])
                    ->default('False')
                    ->required(),

                    RichEditor::make('description')
                    ->label('Descrição')
                    ->required()
                    ->nullable(),

                    Select::make('paciente_id') // Relacionamento com pacientes
                    ->label('Paciente')
                    ->relationship('paciente', 'nome')
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Título')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('scheduled_at')
                    ->label('Data e Hora')
                    ->dateTime()
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->badge(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'canceled' => 'danger',                        
                    }),
                
                  TextColumn::make('paciente.nome') // Relaciona o paciente
                    ->label('Paciente')
                    ->sortable()
                    ->searchable(),

            ])
            ->filters([
                //
            ])
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
            'index' => Pages\ListAgendas::route('/'),
            'create' => Pages\CreateAgenda::route('/create'),
            'edit' => Pages\EditAgenda::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Agendamentos';
    }

    public static function getNavigationLabel(): string
    {
        return 'Agenda';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-calendar';
    }
}
