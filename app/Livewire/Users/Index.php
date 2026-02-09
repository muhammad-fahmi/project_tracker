<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Users')]
class Index extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public function mount(): void
    {
        $this->authorize('viewAny', User::class);
    }

    public function delete(int $userId): void
    {
        $user = User::findOrFail($userId);
        $this->authorize('delete', $user);

        $user->delete();
        session()->flash('message', 'User deleted successfully.');
    }

    public function render()
    {
        $users = User::query()->latest()->paginate(10);

        return view('livewire.users.index', [
            'users' => $users,
        ])->layout('components.layouts.app');
    }
}
