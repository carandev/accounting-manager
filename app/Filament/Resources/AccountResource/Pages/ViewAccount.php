<?php

namespace App\Filament\Resources\AccountResource\Pages;

use App\Filament\Resources\AccountResource;
use App\Filament\Resources\AccountResource\RelationManagers\TransactionsRelationManager;
use App\Models\Transaction;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewAccount extends ViewRecord
{
    protected static string $resource = AccountResource::class;

    protected static ?string $title = 'Detalles de la cuenta';

    protected static ?string $breadcrumb = 'Detalles';

    // Método que se ejecuta para personalizar los detalles de la cuenta
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label('Editar')
                ->icon('heroicon-o-pencil')
                ->color('primary'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name'),
                TextEntry::make('amount')
            ]);
    }
}
