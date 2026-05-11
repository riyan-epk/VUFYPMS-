<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectDomain;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function index()
    {
        $domains = ProjectDomain::withCount('proposals')->latest()->paginate(15);
        return view('admin.domains.index', compact('domains'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:100', 'unique:project_domains,name'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        ProjectDomain::create([
            'name'        => $request->name,
            'description' => $request->description,
            'is_active'   => true,
        ]);

        return back()->with('success', 'Project domain added successfully.');
    }

    public function update(Request $request, int $id)
    {
        $domain = ProjectDomain::findOrFail($id);
        $request->validate([
            'name'        => ['required', 'string', 'max:100', 'unique:project_domains,name,' . $id],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active'   => ['boolean'],
        ]);

        $domain->update([
            'name'        => $request->name,
            'description' => $request->description,
            'is_active'   => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Domain updated successfully.');
    }

    public function destroy(int $id)
    {
        $domain = ProjectDomain::withCount('proposals')->findOrFail($id);
        if ($domain->proposals_count > 0) {
            return back()->with('error', 'Cannot delete domain with existing proposals.');
        }
        $domain->delete();
        return back()->with('success', 'Domain deleted.');
    }
}
