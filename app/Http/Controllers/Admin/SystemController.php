<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class SystemController extends Controller
{
    /** Cache key untuk pesan maintenance yang ditampilkan di halaman 503. */
    private const MESSAGE_KEY = 'maintenance.message';

    public function maintenance()
    {
        $path = storage_path('framework/maintenance.php');
        $payload = app()->isDownForMaintenance() && file_exists($path)
            ? (array) require $path
            : [];

        $status = [
            'active' => app()->isDownForMaintenance(),
            'since' => isset($payload['time']) ? Carbon::createFromTimestamp((int) $payload['time']) : null,
            'secret' => $payload['secret'] ?? null,
            'retry' => $payload['retry'] ?? null,
            'message' => Cache::get(self::MESSAGE_KEY),
        ];

        return view('admin.maintenance', compact('status'));
    }

    public function toggleMaintenance(Request $request)
    {
        $data = $request->validate([
            'action' => ['required', 'in:enable,disable'],
            'message' => ['nullable', 'string', 'max:255'],
            'secret' => ['nullable', 'string', 'max:255', 'alpha_dash'],
            'retry' => ['nullable', 'integer', 'min:0', 'max:3600'],
        ]);

        if ($data['action'] === 'disable') {
            Artisan::call('up');
            Cache::forget(self::MESSAGE_KEY);

            return back()->with('success', 'Maintenance mode dinonaktifkan. Situs kembali online untuk semua pengguna.');
        }

        Artisan::call('down', array_filter([
            '--secret' => $data['secret'] ?? null,
            '--retry' => $data['retry'] ?? null,
        ], fn ($value) => $value !== null && $value !== ''));

        if (! empty($data['message'])) {
            Cache::forever(self::MESSAGE_KEY, $data['message']);
        } else {
            Cache::forget(self::MESSAGE_KEY);
        }

        return back()->with('success', 'Maintenance mode diaktifkan. Pengunjung biasa akan melihat halaman pemeliharaan.');
    }
}
