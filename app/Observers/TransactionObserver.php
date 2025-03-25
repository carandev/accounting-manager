<?php

namespace App\Observers;

use App\Models\Transaction;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        $this->updateAccountAmount($transaction);
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        // Primero, recuperamos la transacción original antes de actualizar el saldo
        $original = $transaction->getOriginal();

        // Si el tipo de transacción cambió, tenemos que ajustar el saldo en función del nuevo tipo
        if ($original['type'] === 'income') {
            $transaction->account->amount -= $original['amount']; // Restamos el monto original
        } elseif ($original['type'] === 'expensive') {
            $transaction->account->amount += $original['amount']; // Sumamos el monto original
        }

        // Ahora, aplicamos el nuevo monto y tipo de transacción
        $this->updateAccountAmount($transaction);
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        // Si se elimina una transacción, restauramos el saldo original
        if ($transaction->type === 'income') {
            $transaction->account->amount -= $transaction->amount;
        } elseif ($transaction->type === 'expensive') {
            $transaction->account->amount += $transaction->amount;
        }

        $transaction->account->save();
    }

    /**
     * Método auxiliar para actualizar el saldo de la cuenta según el tipo de transacción.
     *
     * @param \App\Models\Transaction $transaction
     * @return void
     */
    private function updateAccountAmount(Transaction $transaction)
    {
        $account = $transaction->account;

        if ($transaction->type === 'income') {
            $account->amount += $transaction->amount;
        } elseif ($transaction->type === 'expensive') {
            $account->amount -= $transaction->amount;
        }

        $account->save();
    }
}
