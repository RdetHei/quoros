<?php

namespace App\Http\Controllers;

use App\Models\WriterEarning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class WriterEarningController extends Controller
{
    public function updatePayout(Request $request)
    {
        $validated = $request->validate([
            'method' => 'required|in:bank,paypal',
            'account_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:8',
            'auto_payout' => 'nullable|boolean',
            'min_payout' => 'nullable|numeric|min:0',
        ]);

        Auth::user()->payoutSetting()->updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'method' => $validated['method'],
                'account_name' => $validated['account_name'] ?? null,
                'account_number' => $validated['account_number'] ?? null,
                'currency' => $validated['currency'] ?? 'USD',
                'auto_payout' => $request->boolean('auto_payout'),
                'min_payout' => $validated['min_payout'] ?? 50,
            ]
        );

        return back()->with('success', 'Pengaturan payout berhasil disimpan.');
    }

    public function statement(Request $request): StreamedResponse
    {
        $period = $request->get('period', 'all');
        $query = WriterEarning::where('user_id', Auth::id())->orderByDesc('created_at');

        if ($period === 'year') {
            $query->whereYear('created_at', now()->year);
        } elseif ($period === 'month') {
            $query->whereYear('created_at', now()->year)->whereMonth('created_at', now()->month);
        }

        $rows = $query->get();
        $filename = 'quoros-statement-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Date', 'Type', 'Description', 'Source', 'Amount', 'Status']);
            foreach ($rows as $row) {
                fputcsv($out, [
                    $row->created_at->format('Y-m-d'),
                    $row->type_label,
                    $row->description,
                    $row->source,
                    number_format((float) $row->amount, 2, '.', ''),
                    ucfirst($row->status),
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
