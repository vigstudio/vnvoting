<?php

namespace App\Livewire\Counting;

use App\Models\Election;
use Livewire\Attributes\{Layout, Title};
use Livewire\Component;

class Dashboard extends Component
{
    #[Title('Dashboard Kiểm Phiếu')]
    public function render()
    {
        $activeElections = Election::active()
            ->with(['positions' => fn ($q) => $q->withCount('ballots')])
            ->get();

        return view('livewire.counting.dashboard', [
            'activeElections' => $activeElections,
        ]);
    }
}
