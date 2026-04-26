<?php

namespace App\Http\Controllers;

use App\Models\IncomingInvoice;
use App\Models\OutgoingInvoice;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type', 'all');
        $status = $request->input('status');
        $search = $request->input('search');
        $from = $request->input('from');
        $to = $request->input('to');

        $incoming = collect();
        $outgoing = collect();

        if ($type === 'all' || $type === 'incoming') {
            $query = IncomingInvoice::with(['supplier', 'user'])->select([
                    'id', 'invoice_number', 'invoice_date', 'status', 'user_id', 'supplier_id',
                    'total_amount', 'created_at',
                ]);

            if ($status) { $query->where('status', $status); }
            if ($search) { $query->where('invoice_number', 'like', "%{$search}%"); }
            if ($from) { $query->whereDate('invoice_date', '>=', $from); }
            if ($to) { $query->whereDate('invoice_date', '<=', $to); }

            $incoming = $query->get()->map(function ($inv) {
                return [
                    'id' => $inv->id,
                    'type' => 'incoming',
                    'type_label' => 'Приходная',
                    'type_badge' => 'bg-success',
                    'invoice_number' => $inv->invoice_number,
                    'invoice_date' => $inv->invoice_date,
                    'status' => $inv->status,
                    'counterparty' => $inv->supplier->name ?? '—',
                    'user' => $inv->user->name ?? '—',
                    'amount' => $inv->total_amount,
                    'route' => route('incoming.invoices.show', $inv->id),
                    'created_at' => $inv->created_at,
                ];
            });
        }

        if ($type === 'all' || $type === 'outgoing') { $query = OutgoingInvoice::with(['user'])->select([
                    'id', 'invoice_number', 'invoice_date', 'status', 'user_id', 'recipient', 'created_at', ]);

            if ($status) { $query->where('status', $status); }
            if ($search) { $query->where('invoice_number', 'like', "%{$search}%"); }
            if ($from) { $query->whereDate('invoice_date', '>=', $from); }
            if ($to) { $query->whereDate('invoice_date', '<=', $to); }

            $outgoing = $query->get()->map(function ($inv) {
                return [
                    'id' => $inv->id,
                    'type' => 'outgoing',
                    'type_label' => 'Расходная',
                    'type_badge' => 'bg-warning text-dark',
                    'invoice_number' => $inv->invoice_number,
                    'invoice_date' => $inv->invoice_date,
                    'status' => $inv->status,
                    'counterparty' => $inv->recipient,
                    'user' => $inv->user->name ?? '—',
                    'amount' => null,
                    'route' => route('outgoing.invoices.show', $inv->id),
                    'created_at' => $inv->created_at,
                ];
            });
        }

        $operations = $incoming->concat($outgoing)
            ->sortByDesc('invoice_date')
            ->values();

        $page = $request->input('page', 1);
        $perPage = 20;
        $total = $operations->count();
        $items = $operations->slice(($page - 1) * $perPage, $perPage)->values();

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator($items, $total, $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()] );

        return view('history.index', [ 'operations' => $paginator,
            'filters' => compact('type', 'status', 'search', 'from', 'to'), ]);
    }
}
