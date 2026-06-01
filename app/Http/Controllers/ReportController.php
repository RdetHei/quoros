<?php

namespace App\Http\Controllers;

use App\Enums\ReportReason;
use App\Enums\ReportStatus;
use App\Models\Comment;
use App\Models\Novel;
use App\Models\Report;
use App\Models\User;
use App\Services\ReportableResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    public function __construct(
        private ReportableResolver $resolver,
    ) {}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reportable_type' => ['required', Rule::in(['novel', 'comment', 'user'])],
            'reportable_id' => ['required', 'integer', 'min:1'],
            'reason' => ['required', Rule::enum(ReportReason::class)],
            'details' => ['nullable', 'string', 'max:2000', 'required_if:reason,other'],
        ]);

        $reportable = $this->resolver->resolve(
            $validated['reportable_type'],
            (int) $validated['reportable_id'],
        );

        $this->assertCanReport($reportable);

        $morphClass = $this->resolver->morphClass($validated['reportable_type']);

        $duplicate = Report::query()
            ->where('reporter_id', Auth::id())
            ->where('reportable_type', $morphClass)
            ->where('reportable_id', $reportable->getKey())
            ->where('status', ReportStatus::Pending)
            ->exists();

        if ($duplicate) {
            return back()->with('error', 'You already have a pending report for this content.');
        }

        Report::create([
            'reporter_id' => Auth::id(),
            'reportable_type' => $morphClass,
            'reportable_id' => $reportable->getKey(),
            'reason' => $validated['reason'],
            'details' => $validated['details'] ?? null,
            'status' => ReportStatus::Pending,
        ]);

        return back()->with('success', 'Report submitted successfully. The moderation team will review it.');
    }

    private function assertCanReport(Novel|Comment|User $reportable): void
    {
        $userId = Auth::id();

        if ($reportable instanceof User) {
            if ($reportable->id === $userId) {
                abort(403, 'You cannot report yourself.');
            }

            if ($reportable->role === 'admin') {
                abort(403, 'This content cannot be reported.');
            }

            return;
        }

        if ($reportable instanceof Novel) {
            if ($reportable->author_id === $userId) {
                abort(403, 'You cannot report your own novel.');
            }

            return;
        }

        if ($reportable instanceof Comment && $reportable->user_id === $userId) {
            abort(403, 'You cannot report your own comment.');
        }
    }
}
