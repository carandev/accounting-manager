<?php

namespace App\Filament\Resources\AccountResource\RelationManagers;

use App\Models\Account;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = "transactions";

    protected static ?string $title = "Transacciones";

    protected static ?string $label = "Transaccion";

    public function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $accountId = $this->ownerRecord->id;
        
        return $this->getRelationship()->getQuery()
            ->orWhere('destination_account_id', $accountId);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make("name")
                ->label("Nombre")
                ->required()
                ->maxLength(50),
            Forms\Components\Select::make("type")
                ->label("Tipo")
                ->options([
                    "income" => "Ingresos",
                    "expensive" => "Gastos",
                    "transfer" => "Transferencia entre cuentas",
                ])
                ->required()
                ->reactive(),
            Forms\Components\Select::make("destination_account_id")
                ->label("Cuenta Destino")
                ->options(function () {
                    $currentAccountId = request()->route('record'); // ID de la cuenta actual
                    return Account::where('user_id', Auth::id())
                        ->where('id', '!=', $currentAccountId)
                        ->pluck('name', 'id');
                })
                ->searchable()
                ->visible(fn (Forms\Get $get) => $get('type') === 'transfer')
                ->required(fn (Forms\Get $get) => $get('type') === 'transfer'),
            Forms\Components\TextInput::make("amount")
                ->label("Valor")
                ->required()
                ->numeric()
                ->prefix('$'),
            Forms\Components\DatePicker::make("transaction_date")
                ->label("Fecha de Transacción")
                ->required()
                ->default(now())
                ->displayFormat("d M Y"),
            Select::make("categories")
                ->label("Categorías")
                ->multiple()
                ->relationship("categories", "name")
                ->searchable()
                ->preload()
                ->createOptionForm([
                    Forms\Components\TextInput::make("name")->required(),
                ]),
            Forms\Components\Textarea::make("summary")
                ->label("Detalles")
                ->maxLength(500)
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute("id")
            ->columns([
                Tables\Columns\TextColumn::make("id")->label("ID"),
                Tables\Columns\TextColumn::make("created_at")
                    ->label("F. Registro")
                    ->dateTime("d M Y"),
                Tables\Columns\TextColumn::make("name")
                    ->label("Nombre")
                    ->searchable(),
                Tables\Columns\TextColumn::make("type")->label("Tipo"),
                Tables\Columns\TextColumn::make("direction")
                    ->label("Dirección")
                    ->getStateUsing(function ($record) {
                        $currentAccountId = $this->ownerRecord->id;
                        
                        if ($record->type === 'transfer') {
                            if ($record->account_id == $currentAccountId) {
                                return 'Salida a ' . $record->destinationAccount?->name;
                            } elseif ($record->destination_account_id == $currentAccountId) {
                                return 'Entrada desde ' . $record->account?->name;
                            }
                        }
                        
                        return match($record->type) {
                            'income' => 'Ingreso',
                            'expensive' => 'Gasto',
                            default => 'N/A'
                        };
                    }),
                Tables\Columns\TextColumn::make("amount")
                    ->label("Valor")
                    ->money("COP")
                    ->color(function ($record) {
                        $currentAccountId = $this->ownerRecord->id;
                        
                        if ($record->type === 'income' || 
                            ($record->type === 'transfer' && $record->destination_account_id == $currentAccountId)) {
                            return 'success'; // Verde para ingresos y transferencias entrantes
                        } elseif ($record->type === 'expensive' || 
                                 ($record->type === 'transfer' && $record->account_id == $currentAccountId)) {
                            return 'danger'; // Rojo para gastos y transferencias salientes
                        }
                        
                        return 'gray';
                    }),
                Tables\Columns\TextColumn::make("transaction_date")
                    ->label("Fecha Transacción")
                    ->date("d M Y")
                    ->sortable(),
                Tables\Columns\TextColumn::make("categories.name")->label(
                    "Categorías",
                ),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label("Nueva transaccion"),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label("Editar")
                    ->visible(fn ($record) => $record->account_id == $this->ownerRecord->id),
                Tables\Actions\DeleteAction::make()
                    ->label("Eliminar")
                    ->visible(fn ($record) => $record->account_id == $this->ownerRecord->id),
            ])
            ->bulkActions([]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
