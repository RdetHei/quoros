@extends('layouts.admin')

@php
    $adminTitle = 'Content Logs';
    $adminBreadcrumbs = ['Admin', 'Content Logs'];
@endphp

@section('content')
<section class="max-w-5xl mx-auto bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5">
    <h2 class="font-semibold text-slate-900 dark:text-white">Recent Chapter Uploads</h2>

    <div class="mt-4 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500 dark:text-slate-400">
                <tr>
                    <th class="py-2">Chapter</th>
                    <th class="py-2">Novel</th>
                    <th class="py-2">Author</th>
                    <th class="py-2">Uploaded At</th>
                </tr>
            </thead>
            <tbody class="text-slate-700 dark:text-slate-200">
                @forelse($recentChapters as $chapter)
                    <tr class="border-t border-slate-100 dark:border-slate-800">
                        <td class="py-2">{{ $chapter->title }}</td>
                        <td class="py-2">{{ $chapter->novel?->title ?? 'Unknown' }}</td>
                        <td class="py-2">{{ $chapter->novel?->author?->name ?? 'Unknown' }}</td>
                        <td class="py-2">{{ $chapter->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-4 text-slate-500">No chapter logs found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $recentChapters->links() }}</div>
</section>
@endsection
