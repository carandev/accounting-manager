<?php

namespace App\Filament\Resources\AccountResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    protected static ?string $title = 'Transacciones';

    protected static ?string $label = 'Transaccion';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(50),
                Forms\Components\Select::make('type')
                    ->label('Tipo')
                    ->options(
                        [
                            'income' => 'Ingresos',
                            'expensive' => 'Gastos'
                        ]
                    )
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->label('Valor')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\DatePicker::make('transaction_date')
                    ->label('Fecha de Transacción')
                    ->required()
                    ->default(now())
                    ->displayFormat('d M Y'),
                Select::make('categories')
                    ->label('Categorías')
                    ->multiple()
                    ->relationship('categories', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                    ]),
                Forms\Components\Textarea::make('summary')
                    ->label('Detalles')
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('F. Registro')
                    ->dateTime('d M Y'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo'),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Valor')
                    ->money('COP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Fecha Transacción')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('categories.name')
                    ->label('Categorías')
                    ->sortable(),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nueva transaccion'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Editar'),
                Tables\Actions\DeleteAction::make()
                    ->label('Eliminar'),
            ])
            ->bulkActions([]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
