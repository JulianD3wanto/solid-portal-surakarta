<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationEvent;
use App\Models\DocumentType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function create(DocumentType $documentType): View { abort_unless($documentType->is_active, 404); return view('citizen.applications.create', ['service' => $documentType]); }

    public function store(Request $request, DocumentType $documentType): RedirectResponse
    {
        $data = $request->validate(['applicant_name'=>['required','string','max:120'],'phone'=>['required','string','max:30'],'address'=>['required','string','max:500'],'note'=>['nullable','string','max:1000'],'requirements'=>['required','array','min:'.count($documentType->requirements ?? [])],'requirements.*'=>['required','file','mimes:pdf,jpg,jpeg,png','max:5120']]);
        $files = $data['requirements']; unset($data['requirements']);
        $application = Application::create(['user_id'=>$request->user()->id,'kelurahan_id'=>$request->user()->citizenProfile?->kelurahan_id,'document_type_id'=>$documentType->id,'application_number'=>'DMLS-'.now()->format('Ymd').'-'.strtoupper(Str::random(6)),'status'=>'submitted','submitted_data'=>$data,'citizen_note'=>$data['note'] ?? null,'submitted_at'=>now()]);
        foreach ($files as $index=>$uploadedFile) { $path=$uploadedFile->store('dmls/requirements','local'); \App\Models\ApplicationFile::create(['application_id'=>$application->id,'uploaded_by'=>$request->user()->id,'disk'=>'local','path'=>$path,'original_name'=>$uploadedFile->getClientOriginalName(),'mime_type'=>$uploadedFile->getMimeType(),'size'=>$uploadedFile->getSize(),'sha256'=>hash_file('sha256',$uploadedFile->getRealPath()),'category'=>$documentType->requirements[$index] ?? 'requirement','scan_status'=>'clean','verification_status'=>'pending','is_result'=>false]); }
        ApplicationEvent::create(['application_id'=>$application->id,'user_id'=>$request->user()->id,'event'=>'submitted','to_status'=>'submitted','note'=>'Permohonan berhasil dikirim melalui portal.']);
        \App\Models\AdminNotification::notifyNewApplication($application);
        return redirect()->route('dashboard')->with('success','Permohonan '.$application->application_number.' berhasil dikirim.');
    }

    public function viewFile(Application $application, \App\Models\ApplicationFile $applicationFile)
    {
        abort_unless($application->user_id === request()->user()->id && $applicationFile->application_id === $application->id && ! $applicationFile->is_result, 403);
        $disk = Storage::disk($applicationFile->disk);
        abort_unless($disk->exists($applicationFile->path), 404);
        return response()->file($disk->path($applicationFile->path), ['Content-Type' => $applicationFile->mime_type, 'Content-Disposition' => 'inline; filename="'.addslashes($applicationFile->original_name).'"']);
    }

    public function deleteFile(Application $application, \App\Models\ApplicationFile $applicationFile): RedirectResponse
    {
        abort_unless($application->user_id === request()->user()->id && $applicationFile->application_id === $application->id && ! $applicationFile->is_result && in_array($application->status, ['submitted', 'rejected'], true), 403);
        Storage::disk($applicationFile->disk)->delete($applicationFile->path);
        $applicationFile->update(['path'=>null,'original_name'=>null,'mime_type'=>null,'size'=>null,'sha256'=>null,'verification_status'=>'pending','verification_note'=>null]);
        return back()->with('success', 'Berkas berhasil dihapus.');
    }
    public function edit(Application $application): View
    {
        abort_unless($application->user_id === request()->user()->id && in_array($application->status, ['submitted', 'rejected'], true), 403);
        return view('citizen.applications.edit', ['application' => $application->load(['documentType', 'files'])]);
    }

    public function update(Request $request, Application $application): RedirectResponse
    {
        abort_unless($application->user_id === $request->user()->id && in_array($application->status, ['submitted', 'rejected'], true), 403);
        $data = $request->validate(['applicant_name'=>['required','string','max:120'],'phone'=>['required','string','max:30'],'address'=>['required','string','max:500'],'note'=>['nullable','string','max:1000'],'requirements'=>['nullable','array'],'requirements.*'=>['nullable','file','mimes:pdf,jpg,jpeg,png','max:5120']]);
        $replacementFiles = $data['requirements'] ?? []; unset($data['requirements']);
        foreach ($replacementFiles as $index => $uploadedFile) {
            if (! $uploadedFile) continue;
            $oldFile = $application->files()->where('is_result', false)->orderBy('id')->skip($index)->first();
            if ($oldFile) { if ($oldFile->path) Storage::disk($oldFile->disk)->delete($oldFile->path); $path = $uploadedFile->store('dmls/requirements', 'local'); $oldFile->update(['path'=>$path,'original_name'=>$uploadedFile->getClientOriginalName(),'mime_type'=>$uploadedFile->getMimeType(),'size'=>$uploadedFile->getSize(),'sha256'=>hash_file('sha256',$uploadedFile->getRealPath()),'verification_status'=>'pending','verification_note'=>null]); }
        }


        $from = $application->status;
        $application->update(['submitted_data'=>$data,'citizen_note'=>$data['note'] ?? null,'status'=>'submitted','submitted_at'=>now()]);

        ApplicationEvent::create(['application_id'=>$application->id,'user_id'=>$request->user()->id,'event'=>'resubmitted','from_status'=>$from,'to_status'=>'submitted','note'=>'Permohonan diperbaiki dan dikirim ulang oleh warga.']);
        \App\Models\AdminNotification::notifyNewApplication($application, 'memperbaiki dan mengirim ulang');
        return redirect()->route('dashboard')->with('success','Data permohonan berhasil diperbarui dan dikirim ulang.');
    }
}
