<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncomingInvoiceItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'incoming_invoice_id',
        'product_id',
        'quantity',
        'unit_price',
        'total'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(IncomingInvoice::class, 'incoming_invoice_id');
    }
}
