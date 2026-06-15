<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class VideoConsultationController extends Controller
{
    public function start(RendezVous $rendezvou): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        abort_if(
            ! $user->hasRole('super_admin') && $rendezvou->opticien_id !== $user->id,
            403
        );

        if (! $rendezvou->hasVideoRoom()) {
            $rendezvou->update([
                'video_room' => 'esante-'.Str::uuid(),
                'video_started_at' => now(),
            ]);
        }

        return redirect()->route('admin.video.room', $rendezvou);
    }

    public function room(RendezVous $rendezvou): View
    {
        abort_if(! $rendezvou->hasVideoRoom(), 404);

        /** @var User $user */
        $user = auth()->user();

        abort_if(
            ! $user->hasRole('super_admin') && $rendezvou->opticien_id !== $user->id,
            403
        );

        $rendezvou->load(['patient.user', 'opticien', 'cabinet']);

        return view('video.room', [
            'rendezvous' => $rendezvou,
            'roomName' => $rendezvou->video_room,
            'userName' => $user->name,
            'role' => 'opticien',
        ]);
    }
}
