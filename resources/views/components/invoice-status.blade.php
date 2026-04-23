@props(['status'])

@php
    $cfg = match($status) {
        'draft'     => ['bg-secondary', 'Черновик'],
        'posted'    => ['bg-success', 'Проведена'],
        'cancelled' => ['bg-danger', 'Отменена'],
        default     => ['bg-light text-dark', $status],
    };
@endphp

<span class="badge {{ $cfg[0] }}">{{ $cfg[1] }}</span>
