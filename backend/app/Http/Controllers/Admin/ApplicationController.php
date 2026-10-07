<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationEvent;
use App\Models\ApplicationFile;
use App\Models\CitizenProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Application::with(['user', 'documentType', 'kelurahan'])->latest();
        if ($request->user()->role === 'kelurahan_officer') {
            $kelurahanId = $request->user()->citizenProfile?->kelurahan_id;
            $query->where('kelurahan_id', $kelurahanId);
        }
        return view('admin.applications.index', ['applications' => $query->get()]);
    }

    public function show(Request $request, Application $application): View
    {
        if ($request->user()->role === 'kelurahan_officer') {
            abort_unless($application->kelurahan_id === $request->user()->citizenProfile?->kelurahan_id, 403);
        }
        return view('admin.applications.show', ['application' => $application->load(['user', 'documentType', 'files', 'events.user'])]);
    }

    public function approve(Request $request, Application $application): RedirectResponse
    {
        $request->validate(['note' => ['nullable', 'string', 'max:1000']]);
        $from = $application->status;
        $application->update(['status' => 'approved', 'officer_note' => $request->input('note')]);
        $this->event($application, $request, 'approved', $from, 'approved', $request->input('note'));
        \App\Models\CitizenNotification::notifyUser($application->user_id, $application, 'Permohonan disetujui', 'Permohonan '.$application->application_number.' ('.$application->documentType->name.') disetujui petugas. Surat hasil segera menyusul.');
        return back()->with('success', 'Permohonan berhasil disetujui. Upload surat hasil untuk menyelesaikan proses.');
    }

    public function reject(Request $request, Application $application): RedirectResponse
    {
        $data = $request->validate(['note' => ['required', 'string', 'max:1000']]);
        $from = $application->status;
        $application->update(['status' => 'rejected', 'officer_note' => $data['note']]);
        $this->event($application, $request, 'rejected', $from, 'rejected', $data['note']);
        \App\Models\CitizenNotification::notifyUser($application->user_id, $application, 'Permohonan ditolak', 'Permohonan '.$application->application_number.' ('.$application->documentType->name.') ditolak. Alasan: '.$data['note']);
        return back()->with('success', 'Permohonan ditolak dan catatan telah disimpan.');
    }

    public function verifyFile(Request $request, ApplicationFile $applicationFile): RedirectResponse
    {
        $data = $request->validate(['verification_status' => ['required', 'in:verified,rejected'], 'verification_note' => ['nullable', 'string', 'max:1000']]);
        $applicationFile->update($data);
        return back()->with('success', 'Status berkas berhasil diperbarui.');
    }

    public function viewFile(Request $request, ApplicationFile $applicationFile)
    {
        abort_unless(in_array($request->user()->role, ['admin', 'kelurahan_officer'], true), 403);
        $disk = Storage::disk($applicationFile->disk);
        abort_unless($disk->exists($applicationFile->path), 404);
        return response()->file($disk->path($applicationFile->path), [
            'Content-Type' => $applicationFile->mime_type,
            'Content-Disposition' => 'inline; filename="'.addslashes($applicationFile->original_name).'"',
        ]);
    }

    public function downloadFile(Request $request, ApplicationFile $applicationFile)
    {
        abort_unless(in_array($request->user()->role, ['admin', 'kelurahan_officer'], true), 403);
        abort_unless(Storage::disk($applicationFile->disk)->exists($applicationFile->path), 404);
        return Storage::disk($applicationFile->disk)->download($applicationFile->path, $applicationFile->original_name);
    }

    public function uploadResult(Request $request, Application $application): RedirectResponse
    {
        if (! in_array($application->status, ['approved', 'ready'], true)) {
            return back()->withErrors(['result_file' => 'Setujui permohonan terlebih dahulu sebelum mengunggah surat hasil.']);
        }
        $data = $request->validate(['result_file' => ['required', 'file', 'mimes:pdf', 'max:10240']]);
        $file = $data['result_file'];
        $path = $file->store('dmls/results', 'local');
        ApplicationFile::create([
            'application_id' => $application->id,
            'uploaded_by' => $request->user()->id,
            'disk' => 'local',
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'sha256' => hash_file('sha256', $file->getRealPath()),
            'category' => 'result',
            'scan_status' => 'clean',
            'is_result' => true,
        ]);
        $application->update(['status' => 'completed', 'completed_at' => now()]);
        $this->event($application, $request, 'result_uploaded', 'approved', 'completed', 'Surat hasil telah diunggah oleh admin.');
        \App\Models\CitizenNotification::notifyUser($application->user_id, $application, 'Surat hasil siap', 'Surat hasil untuk permohonan '.$application->application_number.' ('.$application->documentType->name.') sudah dapat diunduh.', route('citizen.applications.download-result', $application));
        return back()->with('success', 'Surat hasil berhasil diunggah dan tersedia untuk warga.');
    }

    public function viewResult(Request $request, Application $application)
    {
        abort_unless($application->user_id === $request->user()->id || in_array($request->user()->role, ['admin', 'content_editor'], true), 403);
        $file = $application->files()->where('is_result', true)->latest()->firstOrFail();
        $disk = Storage::disk($file->disk);
        abort_unless($disk->exists($file->path), 404);
        return response()->file($disk->path($file->path), [
            'Content-Type' => $file->mime_type,
            'Content-Disposition' => 'inline; filename="'.addslashes($file->original_name).'"',
        ]);
    }

    public function downloadResult(Request $request, Application $application)
    {
        abort_unless($application->user_id === $request->user()->id || in_array($request->user()->role, ['admin', 'content_editor']), 403);
        $file = $application->files()->where('is_result', true)->latest()->firstOrFail();
        abort_unless(Storage::disk($file->disk)->exists($file->path), 404);
        return Storage::disk($file->disk)->download($file->path, $file->original_name);
    }

    private function event(Application $application, Request $request, string $event, ?string $from, string $to, ?string $note): void
    {
        ApplicationEvent::create(['application_id' => $application->id, 'user_id' => $request->user()->id, 'event' => $event, 'from_status' => $from, 'to_status' => $to, 'note' => $note]);
    }
}
