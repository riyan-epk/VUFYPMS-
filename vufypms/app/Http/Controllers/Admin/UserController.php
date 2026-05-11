<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->role) $query->where('role', $request->role);
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('vu_id', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->status !== null) $query->where('is_active', $request->status);

        $users = $query->orderBy('role')->orderBy('name')->paginate(20)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'unique:users,email'],
            'vu_id'       => ['nullable', 'string', 'max:50', 'unique:users,vu_id'],
            'role'        => ['required', 'in:admin,supervisor,student'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
            'phone'       => ['nullable', 'string', 'max:20'],
            'department'  => ['nullable', 'string', 'max:100'],
            'designation' => ['nullable', 'string', 'max:100'],
            'is_active'   => ['boolean'],
        ]);

        $validated['password']  = Hash::make($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);

        User::create($validated);
        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(int $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'unique:users,email,' . $id],
            'vu_id'       => ['nullable', 'string', 'max:50', 'unique:users,vu_id,' . $id],
            'role'        => ['required', 'in:admin,supervisor,student'],
            'phone'       => ['nullable', 'string', 'max:20'],
            'department'  => ['nullable', 'string', 'max:100'],
            'designation' => ['nullable', 'string', 'max:100'],
            'password'    => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active');
        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function toggleActive(int $id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User account {$status} successfully.");
    }

    public function destroy(int $id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) return back()->with('error', 'You cannot delete your own account.');
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }
}
