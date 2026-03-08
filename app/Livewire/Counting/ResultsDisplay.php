<?php

namespace App\Livewire\Counting;

use App\Models\Ballot;
use App\Services\VoteCounter;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ResultsDisplay extends Component
{
    public Ballot $ballot;

    protected VoteCounter $counter;

    public function boot(VoteCounter $counter): void
    {
        $this->counter = $counter;
    }

    #[Computed]
    public function results(): array
    {
        return $this->counter->getResults($this->ballot)->toArray();
    }

    public function render()
    {
        return view('livewire.counting.results-display');
    }
}
