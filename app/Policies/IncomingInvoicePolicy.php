<?php

namespace App\Policies;

use App\Models\User;
use App\Models\IncomingInvoice;

class IncomingInvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, IncomingInvoice $invoice): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'storekeeper']);
    }

    public function update(User $user, IncomingInvoice $invoice): bool
    {
        return in_array($user->role, ['admin', 'storekeeper']) && $invoice->isDraft();
    }

    public function delete(User $user, IncomingInvoice $invoice): bool
    {
        return $user->role === 'admin';
    }

    public function post(User $user, IncomingInvoice $invoice): bool
    {
        return in_array($user->role, ['admin', 'storekeeper']);
    }

    public function cancel(User $user, IncomingInvoice $invoice): bool
    {
        return in_array($user->role, ['admin', 'storekeeper']);
    }
}
