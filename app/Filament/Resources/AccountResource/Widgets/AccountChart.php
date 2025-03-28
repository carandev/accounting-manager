<?php

namespace App\Filament\Resources\AccountResource\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class AccountChart extends ChartWidget
{
    protected static ?string $heading = 'Gastos por categoria';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $total_expensives = Transaction::query()
            ->where([
                ['type', 'expensive'],
                ['created_at', '>=', now()->startOfMonth()],
                ['created_at', '<=', now()->endOfMonth()]
            ])
            ->whereHas('account', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->with('categories') // Cargar categorías asociadas
            ->get()
            ->flatMap(function ($transaction) {
                return $transaction->categories->map(function ($category) use ($transaction) {
                    return [
                        'category_name' => $category->name,
                        'amount' => $transaction->amount, // Tomamos el amount de la transacción
                    ];
                });
            })
            ->groupBy('category_name')
            ->map(function ($transactions, $categoryName) {
                $totalAmount = collect($transactions)->sum('amount');
                return [
                    'category_name' => $categoryName,
                    'amount' => $totalAmount,
                    'color' => $this->getColorForAmount($totalAmount)
                ];
            })
            ->values();

        return [
            'labels' => $total_expensives->pluck('category_name')->toArray(),
            'datasets' => [
                [
                    'label' => 'Gastos',
                    'data' => $total_expensives->pluck('amount')->toArray(),
                    'backgroundColor' => $total_expensives->pluck('color')->toArray(),
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

private function getColorForAmount($amount): string
    {
        // Definir los valores mínimo y máximo esperados (puedes ajustarlos según tu caso)
        $min = 0;
        $max = 600_000; // Ajusta esto según tu dataset

        // Normalizar el valor entre 0 y 1
        $normalized = min(1, max(0, ($amount - $min) / ($max - $min)));

        // Interpolación de color entre verde (#2ecc71) y rojo (#e74c3c)
        $r = (int) (46 + ($normalized * (231 - 46)));  // De 46 a 231 (verde a rojo)
        $g = (int) (204 - ($normalized * 204));        // De 204 a 0 (verde a rojo)
        $b = (int) (113 - ($normalized * 113));        // De 113 a 60 (verde a rojo)

        return sprintf("#%02X%02X%02X", $r, $g, $b);
    }
}
