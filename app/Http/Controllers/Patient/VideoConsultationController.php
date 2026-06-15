<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\RendezVous;
use Illuminate\View\View;

class VideoConsultationController extends Controller
{
    public function room(RendezVous $rendezvou): View
    {
        abort_if(! $rendezvou->hasVideoRoom(), 404);

        $patient = auth()->user()->patient;
        abort_if($rendezvou->patient_id !== $patient->id, 403);

        $rendezvou->load(['patient.user', 'opticien', 'cabinet']);

        return view('video.room', [
            'rendezvous' => $rendezvou,
            'roomName' => $rendezvou->video_room,
            'userName' => auth()->user()->name,
            'role' => 'patient',
        ]);
    }
}
