<?php

namespace App\Policies;

use App\Models\OutgoingInvoice;
use App\Models\User;

class OutgoingInvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, OutgoingInvoice $invoice): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'storekeeper']);
    }

    public function update(User $user, OutgoingInvoice $invoice): bool
    {
        return in_array($user->role, ['admin', 'storekeeper'])
            && $invoice->isDraft();
    }

    public function post(User $user, OutgoingInvoice $invoice): bool
    {
        return in_array($user->role, ['admin', 'storekeeper']);
    }

    public function cancel(User $user, OutgoingInvoice $invoice): bool
    {
        return in_array($user->role, ['admin', 'storekeeper']);
    }

    public function delete(User $user, OutgoingInvoice $invoice): bool
    {
        return $user->isAdmin();
    }
}
