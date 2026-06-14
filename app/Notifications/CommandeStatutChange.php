<?php

namespace App\Notifications;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommandeStatutChange extends Notification
{
    use Queueable;

    public function __construct(public Commande $commande) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $labels = [
            'en_attente' => 'en attente',
            'confirmee' => 'confirmée',
            'en_preparation' => 'en préparation',
            'prete' => 'prête',
            'livree' => 'livrée',
            'annulee' => 'annulée',
        ];

        $label = $labels[$this->commande->statut] ?? $this->commande->statut;

        return [
            'type' => 'commande',
            'titre' => 'Commande '.$label,
            'message' => 'Votre commande '.$this->commande->numero.' est maintenant '.$label.'.',
            'url' => route('patient.commandes.index'),
            'commande_id' => $this->commande->id,
        ];
    }
}
