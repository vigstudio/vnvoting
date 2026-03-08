<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class UserManager extends Component
{
    use WithPagination;

    public $name = '';
    public $email = '';
    public $password = '';
    public $role = 'vote_counter'; // Mặc định là nhân viên kiểm phiếu

    public $search = '';

    // Mở modal
    public $isModalOpen = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,vote_counter',
        ];
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['name', 'email', 'password', 'role']);
        $this->role = 'vote_counter';
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function saveUser()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ]);

        $this->closeModal();
        $this->resetPage(); // Quay lại trang 1
    }

    public function deleteUser($userId)
    {
        // Không xóa chính mình
        if (auth()->id() == $userId) {
            return;
        }

        User::find($userId)?->delete();
    }

    public function render()
    {
        // List users
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.user-manager', [
            'users' => $users
        ])->layout('layouts.app');
    }
}
