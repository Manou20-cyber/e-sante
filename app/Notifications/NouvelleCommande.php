<?php

namespace App\Notifications;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouvelleCommande extends Notification
{
    use Queueable;

    public function __construct(public Commande $commande) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'commande',
            'titre' => 'Nouvelle commande reçue',
            'message' => 'Le patient '.$this->commande->patient->user->name.' a passé la commande '.$this->commande->numero.' ('
                .number_format($this->commande->montant_total, 0, ',', ' ').' XAF).',
            'url' => route('admin.commandes.index'),
            'commande_id' => $this->commande->id,
        ];
    }
}
