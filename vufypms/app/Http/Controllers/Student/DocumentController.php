<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Notification;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    private function getTeam()
    {
        $member = TeamMember::where('student_id', Auth::id())->where('status', 'active')->first();
        return $member ? $member->team : null;
    }

    public function index()
    {
        $team = $this->getTeam();
        if (!$team) return redirect()->route('student.team.index')->with('error', 'Join a team first.');

        $documents = Document::where('team_id', $team->id)
            ->where('status', 'active')
            ->with('uploader')
            ->latest()
            ->get()
            ->groupBy('type');

        return view('student.documents.index', compact('team', 'documents'));
    }

    public function store(Request $request)
    {
        $team = $this->getTeam();
        if (!$team) return back()->with('error', 'Join a team first.');

        $request->validate([
            'type'  => ['required', 'in:proposal,srs,design,progress_report,final_report,presentation,other'],
            'file'  => ['required', 'file', 'max:10240', 'mimes:pdf,doc,docx,ppt,pptx,zip,txt'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $file     = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $path     = $file->storeAs('documents/' . $team->id, $fileName, 'public');

        $version = Document::where('team_id', $team->id)->where('type', $request->type)->where('status', 'active')->count() + 1;

        Document::where('team_id', $team->id)->where('type', $request->type)->where('status', 'active')->update(['status' => 'superseded']);

        Document::create([
            'team_id'       => $team->id,
            'uploaded_by'   => Auth::id(),
            'type'          => $request->type,
            'original_name' => $file->getClientOriginalName(),
            'file_path'     => $path,
            'file_size'     => $file->getSize(),
            'version'       => $version,
            'notes'         => $request->notes,
        ]);

        if ($team->supervisor_id) {
            Notification::send(
                $team->supervisor_id,
                'Document Uploaded',
                "Team \"{$team->name}\" uploaded a new " . ucfirst(str_replace('_', ' ', $request->type)) . " document.",
                'info',
                route('supervisor.teams.documents', $team->id)
            );
        }

        return back()->with('success', 'Document uploaded successfully.');
    }

    public function download(int $id)
    {
        $team     = $this->getTeam();
        $document = Document::where('id', $id)->where('team_id', $team->id)->firstOrFail();

        if (!Storage::disk('public')->exists($document->file_path)) {
            return back()->with('error', 'File not found on server.');
        }

        return Storage::disk('public')->download($document->file_path, $document->original_name);
    }

    public function destroy(int $id)
    {
        $team     = $this->getTeam();
        $document = Document::where('id', $id)->where('team_id', $team->id)->firstOrFail();

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return back()->with('success', 'Document deleted.');
    }
}
