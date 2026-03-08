<?php

namespace App\Livewire\Admin;

use App\Models\Election;
use Livewire\Attributes\{Layout, Title};
use Livewire\Component;
use Livewire\WithPagination;

class ElectionIndex extends Component
{
    use WithPagination;

    public string $search = '';

    #[Title('Quản lý Cuộc Bầu Cử')]
    #[Layout('layouts.app')]
    public function render()
    {
        $elections = Election::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->withCount('positions')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.election-index', [
            'elections' => $elections,
        ]);
    }

    public function delete(Election $election): void
    {
        $election->delete();
        session()->flash('success', 'Đã xóa cuộc bầu cử thành công.');
    }
}
