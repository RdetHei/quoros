<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReportStatus;
use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $reports = Report::query()
            ->with(['reporter', 'reviewer', 'reportable'])
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $counts = [
            'pending' => Report::where('status', ReportStatus::Pending)->count(),
            'reviewed' => Report::where('status', ReportStatus::Reviewed)->count(),
            'dismissed' => Report::where('status', ReportStatus::Dismissed)->count(),
        ];

        return view('admin.reports.index', compact('reports', 'status', 'counts'));
    }

    public function update(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::enum(ReportStatus::class)],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($validated['status'] === ReportStatus::Pending->value) {
            return back()->with('error', 'Status cannot be returned to pending.');
        }

        $report->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? $report->admin_notes,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Report updated successfully.');
    }

    public function banUser(Request $request, User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Admin cannot be banned.');
        }

        $validated = $request->validate([
            'banned_until' => ['nullable', 'date', 'after:now'],
            'ban_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $hasExpiry = ! empty($validated['banned_until']);

        $user->update([
            'is_banned' => ! $hasExpiry,
            'banned_until' => $hasExpiry ? $validated['banned_until'] : null,
            'ban_reason' => $validated['ban_reason'] ?? null,
        ]);

        $label = 'banned';

        return back()->with('success', "User {$user->name} has been {$label}.");
    }

    public function unban(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Admin cannot be banned.');
        }

        $user->update([
            'is_banned' => false,
            'banned_until' => null,
            'ban_reason' => null,
        ]);

        return back()->with('success', "Ban on {$user->name} has been lifted.");
    }
}
