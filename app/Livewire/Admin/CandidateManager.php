<?php

namespace App\Livewire\Admin;

use App\Models\Candidate;
use App\Models\Position;
use Livewire\Attributes\Rule;
use Livewire\Component;

class CandidateManager extends Component
{
    public Position $position;

    public ?Candidate $editingCandidate = null;

    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('nullable|string')]
    public string $description = '';

    #[Rule('required|integer|min:0')]
    public int $sort_order = 0;

    public function mount(Position $position): void
    {
        $this->position = $position->load(['election', 'candidates']);
    }

    public function render()
    {
        return view('livewire.admin.candidate-manager', [
            'candidates' => $this->position->candidates()->orderBy('sort_order')->get(),
        ]);
    }

    public function edit(Candidate $candidate): void
    {
        $this->editingCandidate = $candidate;
        $this->name = $candidate->name;
        $this->description = $candidate->description ?? '';
        $this->sort_order = $candidate->sort_order;
    }

    public function cancel(): void
    {
        $this->editingCandidate = null;
        $this->reset(['name', 'description', 'sort_order']);
    }

    public function save(): void
    {
        $this->validate();

        if ($this->editingCandidate) {
            $this->editingCandidate->update([
                'name' => $this->name,
                'description' => $this->description ?: null,
                'sort_order' => $this->sort_order,
            ]);

            session()->flash('success', 'Đã cập nhật ứng viên.');
        } else {
            $this->position->candidates()->create([
                'name' => $this->name,
                'description' => $this->description ?: null,
                'sort_order' => $this->sort_order,
            ]);

            session()->flash('success', 'Đã thêm ứng viên mới.');
        }

        $this->cancel();
    }

    public function delete(Candidate $candidate): void
    {
        $candidate->delete();
        session()->flash('success', 'Đã xóa ứng viên.');
    }

    public function moveUp(Candidate $candidate): void
    {
        $candidate->decrement('sort_order');
    }

    public function moveDown(Candidate $candidate): void
    {
        $candidate->increment('sort_order');
    }
}
