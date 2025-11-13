<?php

namespace App\Livewire\HRD;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.h-r-d.dashboard')
            ->layout('components.layouts.app', ['title' => 'HRD Dashboard']);
    }
}
