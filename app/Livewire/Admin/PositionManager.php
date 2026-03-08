<?php

namespace App\Livewire\Admin;

use App\Models\Election;
use App\Models\Position;
use Livewire\Attributes\Rule;
use Livewire\Component;

class PositionManager extends Component
{
    public Election $election;

    public ?Position $editingPosition = null;

    #[Rule('required|string|max:255')]
    public string $title = '';

    #[Rule('required|string|size:7')]
    public string $ballot_color = '#FFFFFF';

    #[Rule('required|integer|min:1')]
    public int $max_votes = 1;

    #[Rule('required|integer|min:0')]
    public int $sort_order = 0;

    public function mount(Election $election): void
    {
        $this->election = $election->load('positions');
    }

    public function render()
    {
        return view('livewire.admin.position-manager', [
            'positions' => $this->election->positions()->orderBy('sort_order')->get(),
        ]);
    }

    public function edit(Position $position): void
    {
        $this->editingPosition = $position;
        $this->title = $position->title;
        $this->ballot_color = $position->ballot_color;
        $this->max_votes = $position->max_votes;
        $this->sort_order = $position->sort_order;
    }

    public function cancel(): void
    {
        $this->editingPosition = null;
        $this->reset(['title', 'ballot_color', 'max_votes', 'sort_order']);
    }

    public function save(): void
    {
        $this->validate();

        if ($this->editingPosition) {
            $this->editingPosition->update([
                'title' => $this->title,
                'ballot_color' => $this->ballot_color,
                'max_votes' => $this->max_votes,
                'sort_order' => $this->sort_order,
            ]);

            session()->flash('success', 'Đã cập nhật cấp chức vụ.');
        } else {
            $this->election->positions()->create([
                'title' => $this->title,
                'ballot_color' => $this->ballot_color,
                'max_votes' => $this->max_votes,
                'sort_order' => $this->sort_order,
            ]);

            session()->flash('success', 'Đã thêm cấp chức vụ mới.');
        }

        $this->cancel();
    }

    public function delete(Position $position): void
    {
        $position->delete();
        session()->flash('success', 'Đã xóa cấp chức vụ.');
    }
}
