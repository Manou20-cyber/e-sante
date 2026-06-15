<?php

namespace App\Notifications;

use App\Models\CabinetOptique;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouveauMessageRecu extends Notification
{
    use Queueable;

    public function __construct(public User $expediteur, public ?CabinetOptique $cabinet = null) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'message',
            'titre' => 'Nouveau message',
            'message' => 'Vous avez reçu un message de '.($this->cabinet?->nom ?? $this->expediteur->name).'.',
            'url' => $this->cabinet
                ? route('patient.messages.show', $this->cabinet->uuid)
                : route('patient.messages.index'),
            'expediteur_id' => $this->expediteur->id,
        ];
    }
}
