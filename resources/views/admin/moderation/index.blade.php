@extends('layouts.admin')

@php
    $adminTitle = 'Novel Moderation';
    $adminBreadcrumbs = ['Admin', 'Content Moderation'];
@endphp

@section('content')
<section class="max-w-5xl mx-auto bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5">
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-slate-900 dark:text-white">All Novels</h2>
        <p class="text-sm text-amber-600 dark:text-amber-400">Pending reports: {{ number_format($pendingReports) }}</p>
    </div>

    <div class="mt-4 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500 dark:text-slate-400">
                <tr>
                    <th class="py-2">Title</th>
                    <th class="py-2">Author</th>
                    <th class="py-2">Views</th>
                    <th class="py-2">Rating</th>
                </tr>
            </thead>
            <tbody class="text-slate-700 dark:text-slate-200">
                @forelse($novels as $novel)
                    <tr class="border-t border-slate-100 dark:border-slate-800">
                        <td class="py-2">{{ $novel->title }}</td>
                        <td class="py-2">{{ $novel->author?->name ?? 'Unknown' }}</td>
                        <td class="py-2">{{ number_format($novel->view_count) }}</td>
                        <td class="py-2">{{ number_format($novel->rating_avg, 1) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-4 text-slate-500">No novels available.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $novels->links() }}</div>
</section>
@endsection
