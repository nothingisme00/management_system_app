<?php

namespace App\Livewire\Karyawan;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.karyawan.dashboard')
            ->layout('layouts.app', ['title' => 'Karyawan Dashboard']);
    }
}
