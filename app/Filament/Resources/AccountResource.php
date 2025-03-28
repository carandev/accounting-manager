<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountResource\Pages\CreateAccount;
use App\Filament\Resources\AccountResource\Pages\EditAccount;
use App\Filament\Resources\AccountResource\Pages\ListAccounts;
use App\Filament\Resources\AccountResource\Pages\ViewAccount;
use App\Filament\Resources\AccountResource\RelationManagers\TransactionsRelationManager;
use App\Filament\Resources\AccountResource\Widgets\AccountChart;
use App\Filament\Resources\AccountResource\Widgets\AccountOverview;
use App\Models\Account;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    protected static ?string $label = 'Cuenta';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(50),
                TextInput::make('amount')
                    ->label('Saldo')
                    ->readOnly()
                    ->default(0),
                Hidden::make('user_id')
                    ->default(Auth::id())
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->where('user_id', Auth::id()))
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('Saldo')
                    ->sortable()
                    ->money('COP'),
                TextColumn::make('categories.name')
                    ->label('Categorías')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('F. Registro')
                    ->dateTime('d M Y')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Consultar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        if (request()->routeIs('filament.dashboard.resources.accounts.edit')) {
            return [];
        }

        return [
            TransactionsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAccounts::route('/'),
            'create' => CreateAccount::route('/create'),
            'view' => ViewAccount::route('/{record}'),
            'edit' => EditAccount::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            AccountChart::class
        ];
    }
}
