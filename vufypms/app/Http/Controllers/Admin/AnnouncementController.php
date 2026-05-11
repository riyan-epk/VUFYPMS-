<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('creator')->latest()->paginate(15);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'content'     => ['required', 'string'],
            'type'        => ['required', 'in:general,deadline,evaluation,schedule'],
            'is_public'   => ['boolean'],
            'target_role' => ['required', 'in:all,student,supervisor'],
            'published_at'=> ['nullable', 'date'],
            'expires_at'  => ['nullable', 'date', 'after:published_at'],
        ]);

        $validated['created_by']  = Auth::id();
        $validated['is_public']   = $request->boolean('is_public');
        $validated['published_at']= $validated['published_at'] ?? now();

        $announcement = Announcement::create($validated);

        $roles = $request->target_role === 'all' ? ['student', 'supervisor', 'admin'] : [$request->target_role];
        $users = User::whereIn('role', $roles)->where('is_active', true)->get();

        foreach ($users as $user) {
            Notification::send($user->id, 'New Announcement: ' . $announcement->title, substr(strip_tags($announcement->content), 0, 150), 'info');
        }

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement published.');
    }

    public function edit(int $id)
    {
        $announcement = Announcement::findOrFail($id);
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, int $id)
    {
        $announcement = Announcement::findOrFail($id);
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'content'     => ['required', 'string'],
            'type'        => ['required', 'in:general,deadline,evaluation,schedule'],
            'is_public'   => ['boolean'],
            'target_role' => ['required', 'in:all,student,supervisor'],
            'published_at'=> ['nullable', 'date'],
            'expires_at'  => ['nullable', 'date'],
        ]);
        $validated['is_public'] = $request->boolean('is_public');
        $announcement->update($validated);
        return redirect()->route('admin.announcements.index')->with('success', 'Announcement updated.');
    }

    public function destroy(int $id)
    {
        Announcement::findOrFail($id)->delete();
        return back()->with('success', 'Announcement deleted.');
    }
}
