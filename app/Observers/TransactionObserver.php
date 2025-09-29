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

        // Revertimos el efecto de la transacción original
        $this->revertAccountAmount($transaction, $original);

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
        } elseif ($transaction->type === 'transfer' && $transaction->destination_account_id) {
            // Para transferencias, devolvemos el dinero a la cuenta origen
            $transaction->account->amount += $transaction->amount;
            // Y lo quitamos de la cuenta destino
            $destinationAccount = $transaction->destinationAccount;
            if ($destinationAccount) {
                $destinationAccount->amount -= $transaction->amount;
                $destinationAccount->save();
            }
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
        } elseif ($transaction->type === 'transfer' && $transaction->destination_account_id) {
            // Para transferencias, quitamos el dinero de la cuenta origen
            $account->amount -= $transaction->amount;
            
            // Y lo agregamos a la cuenta destino
            $destinationAccount = $transaction->destinationAccount;
            if ($destinationAccount) {
                $destinationAccount->amount += $transaction->amount;
                $destinationAccount->save();
            }
        }

        $account->save();
    }

    /**
     * Método auxiliar para revertir el efecto de una transacción en las cuentas.
     *
     * @param \App\Models\Transaction $transaction
     * @param array $original
     * @return void
     */
    private function revertAccountAmount(Transaction $transaction, array $original)
    {
        $account = $transaction->account;

        if ($original['type'] === 'income') {
            $account->amount -= $original['amount'];
        } elseif ($original['type'] === 'expensive') {
            $account->amount += $original['amount'];
        } elseif ($original['type'] === 'transfer' && $original['destination_account_id']) {
            // Para transferencias, devolvemos el dinero a la cuenta origen
            $account->amount += $original['amount'];
            
            // Y lo quitamos de la cuenta destino original
            $originalDestinationAccount = \App\Models\Account::find($original['destination_account_id']);
            if ($originalDestinationAccount) {
                $originalDestinationAccount->amount -= $original['amount'];
                $originalDestinationAccount->save();
            }
        }

        $account->save();
    }
}
