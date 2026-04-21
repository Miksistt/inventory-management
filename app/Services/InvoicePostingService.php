<?php

namespace App\Services;

use App\Models\IncomingInvoice;
use Illuminate\Support\Facades\DB;

class InvoicePostingService
{
    public function post(IncomingInvoice $invoice): bool
    {
        if (!$invoice->isDraft()) {
            return false;
        }

        DB::transaction(function () use ($invoice) {
            foreach ($invoice->items as $item) {
                $item->product->increment('stock_quantity', $item->quantity);
            }
            $invoice->update(['status' => 'posted']);
        });

        return true;
    }

    public function cancel(IncomingInvoice $invoice): bool
    {
        if (!$invoice->isPosted()) {
            return false;
        }

        foreach ($invoice->items as $item) {
            if ($item->product->stock_quantity < $item->quantity) {
                throw new \Exception(
                    "Недостаточно остатка для товара «{$item->product->name}»: " .
                    "на складе {$item->product->stock_quantity} {$item->product->unit->abbreviation}, " .
                    "требуется {$item->quantity}."
                );
            }
        }

        DB::transaction(function () use ($invoice) {
            foreach ($invoice->items as $item) {
                $item->product->decrement('stock_quantity', $item->quantity);
            }
            $invoice->update(['status' => 'cancelled']);
        });

        return true;
    }

    public function canPost(IncomingInvoice $invoice): bool
    {
        return $invoice->isDraft() && $invoice->items->isNotEmpty();
    }

    public function canCancel(IncomingInvoice $invoice): bool
    {
        if (!$invoice->isPosted()) {
            return false;
        }

        foreach ($invoice->items as $item) {
            if ($item->product->stock_quantity < $item->quantity) {
                return false;
            }
        }

        return true;
    }
}
