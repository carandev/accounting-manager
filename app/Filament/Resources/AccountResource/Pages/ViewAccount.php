<?php

namespace App\Filament\Resources\AccountResource\Pages;

use App\Filament\Resources\AccountResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewAccount extends ViewRecord
{
    protected static string $resource = AccountResource::class;

    protected static ?string $title = 'Detalles de la cuenta';

    protected static ?string $breadcrumb = 'Detalles';

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name')
                    ->icon('grommet-tag')
                    ->label('Nombre'),
                TextEntry::make('amount')
                    ->icon('grommet-money')
                    ->label('Saldo')
                    ->money('COP')
            ]);
    }
}
