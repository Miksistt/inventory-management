<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outgoing_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outgoing_invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->decimal('quantity', 10, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outgoing_invoice_items');
    }
};
