<?php

namespace App\Livewire\Admin;

use App\Models\Election;
use Livewire\Attributes\{Layout, Rule, Title};
use Livewire\Component;

class ElectionForm extends Component
{
    public ?Election $election = null;

    #[Rule('required|string|max:255')]
    public string $title = '';

    #[Rule('nullable|string')]
    public string $description = '';

    #[Rule('nullable|date')]
    public string $starts_at = '';

    #[Rule('nullable|date|after:starts_at')]
    public string $ends_at = '';

    #[Rule('boolean')]
    public bool $is_active = true;

    public function mount(?Election $election = null): void
    {
        if ($election) {
            $this->election = $election;
            $this->title = $election->title;
            $this->description = $election->description ?? '';
            $this->starts_at = $election->starts_at?->format('Y-m-d') ?? '';
            $this->ends_at = $election->ends_at?->format('Y-m-d') ?? '';
            $this->is_active = $election->is_active;
        }
    }

    #[Title('Tạo/Sửa Cuộc Bầu Cử')]
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.admin.election-form');
    }

    public function save(): void
    {
        $this->validate();

        if ($this->election) {
            $this->election->update([
                'title' => $this->title,
                'description' => $this->description ?: null,
                'starts_at' => $this->starts_at ?: null,
                'ends_at' => $this->ends_at ?: null,
                'is_active' => $this->is_active,
            ]);

            session()->flash('success', 'Đã cập nhật cuộc bầu cử thành công.');
        } else {
            $this->election = Election::create([
                'title' => $this->title,
                'description' => $this->description ?: null,
                'starts_at' => $this->starts_at ?: null,
                'ends_at' => $this->ends_at ?: null,
                'is_active' => $this->is_active,
            ]);

            session()->flash('success', 'Đã tạo cuộc bầu cử thành công. Bây giờ hãy thêm các cấp chức vụ.');
        }

        $this->redirectRoute('admin.elections.edit', ['election' => $this->election]);
    }
}
