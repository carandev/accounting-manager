<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
protected function getStats(): array
    {
        $total_expensives = Transaction::query()
            ->where([
                ['type', 'expensive'],
                ['transaction_date', '>=', now()->startOfMonth()],
                ['transaction_date', '<=', now()->endOfMonth()]
            ])
            ->whereHas('account', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->sum('amount');

        return [
            Stat::make('Gastos', $this->formatCurrency($total_expensives))
                ->description('Cantidad gastada este mes')
                ->color($this->getColorForAmount($total_expensives)),
        ];
    }

    private function formatCurrency($amount): string
    {
        return '$' . number_format($amount, 0, ',', '.'); // Formato COP sin decimales
    }

    private function getColorForAmount($amount): string
    {
        if ($amount >= 1000000) {
            return 'danger'; // Rojo si es muy alto
        } elseif ($amount >= 500000) {
            return 'warning'; // Naranja si es medio
        }
        return 'success'; // Verde si es bajo
    }
}
