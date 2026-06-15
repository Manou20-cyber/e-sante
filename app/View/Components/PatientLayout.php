<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class PatientLayout extends Component
{
    public function __construct(public string $title = 'Mon espace') {}

    public function render(): View
    {
        return view('layouts.patient');
    }
}
