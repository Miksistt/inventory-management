<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutgoingInvoiceItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'outgoing_invoice_id',
        'product_id',
        'quantity',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(OutgoingInvoice::class, 'outgoing_invoice_id');
    }
}
