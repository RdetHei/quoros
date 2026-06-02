<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementsController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->paginate(20);

        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:2000'],
            'type' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
            'link' => ['nullable', 'string', 'max:1000'],
        ]);

        Announcement::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'type' => $data['type'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? false),
            'link' => $data['link'] ?? null,
        ]);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully!');
    }
}

