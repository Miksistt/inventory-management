<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IncomingInvoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'supplier_id',
        'user_id',
        'comment',
        'status',
        'total_amount'
    ];

    protected $casts = [
        'invoice_date' => 'date',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(IncomingInvoiceItem::class);
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isPosted(): bool
    {
        return $this->status === 'posted';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public static function generateNumber(): string
    {
        $last = self::latest()->first();
        $num = $last ? ((int) substr($last->invoice_number, -6)) + 1 : 1;
        return 'IN-' . date('Ym') . '-' . str_pad((string) $num, 6, '0', STR_PAD_LEFT);
    }
}
