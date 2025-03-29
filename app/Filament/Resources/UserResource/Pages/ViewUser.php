<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected static ?string $title = 'Detalles del usuario';

    protected static ?string $breadcrumb = 'Detalles';

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name')
                    ->icon('heroicon-o-user')
                    ->label('Nombre'),
                TextEntry::make('email')
                    ->icon('heroicon-o-envelope')
                    ->label('Correo'),
                TextEntry::make('roles.name')
                    ->icon('heroicon-o-shield-check')
                    ->label('Roles')
                    ->badge()
                    ->color('success'),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->icon('heroicon-o-pencil')
                ->label('Editar'),
        ];
    }
} 