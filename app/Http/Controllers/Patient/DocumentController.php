<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\StoreDocumentRequest;
use App\Models\DocumentMedical;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DocumentController extends Controller
{
    public function index(): View
    {
        $patient = auth()->user()->patient;

        $documents = $patient->documents()
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('patient.documents.index', compact('documents'));
    }

    public function store(StoreDocumentRequest $request): RedirectResponse
    {
        $patient = auth()->user()->patient;

        $chemin = $request->file('fichier')->store("documents/patient-{$patient->id}", 'local');

        DocumentMedical::create([
            'patient_id' => $patient->id,
            'dossier_id' => $patient->dossierMedical?->id,
            'uploaded_by' => auth()->id(),
            'nom' => $request->nom,
            'type' => $request->type,
            'chemin_fichier' => $chemin,
            'mime_type' => $request->file('fichier')->getMimeType(),
            'taille' => $request->file('fichier')->getSize(),
            'description' => $request->description,
        ]);

        return back()->with('success', 'Document ajouté avec succès.');
    }

    public function download(DocumentMedical $document): BinaryFileResponse
    {
        abort_if($document->patient_id !== auth()->user()->patient->id, 403);
        abort_unless(Storage::disk('local')->exists($document->chemin_fichier), 404);

        return response()->download(
            Storage::disk('local')->path($document->chemin_fichier),
            $document->nom.'.'.pathinfo($document->chemin_fichier, PATHINFO_EXTENSION),
            ['Content-Type' => $document->mime_type]
        );
    }
}
