<?php

namespace App\Notifications;

use App\Models\RendezVous;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RdvStatutChange extends Notification
{
    use Queueable;

    public function __construct(public RendezVous $rdv) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $labels = [
            'en_attente' => 'en attente',
            'confirme' => 'confirmé',
            'annule' => 'annulé',
            'termine' => 'terminé',
        ];

        $statut = $labels[$this->rdv->statut] ?? $this->rdv->statut;

        return [
            'type' => 'rdv',
            'titre' => 'Rendez-vous '.$statut,
            'message' => 'Votre rendez-vous du '.$this->rdv->date->format('d/m/Y à H\hi').' est maintenant '.$statut.'.',
            'url' => route('patient.rendezvous.index'),
            'rdv_id' => $this->rdv->id,
        ];
    }
}
