<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('User Form')]
class Edit extends Component
{
    use AuthorizesRequests;

    public ?User $user = null;

    public string $name = '';

    public string $email = '';

    public string $role = 'staff';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(?User $user = null): void
    {
        $this->user = $user;

        if ($user) {
            $this->authorize('update', $user);
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->role;
        } else {
            $this->authorize('create', User::class);
        }
    }

    public function save()
    {
        $data = $this->validate($this->rules());

        if ($this->password !== '') {
            $data['password'] = Hash::make($this->password);
        } elseif ($this->user) {
            unset($data['password']);
        }

        if ($this->user) {
            $this->user->update($data);
            session()->flash('message', 'User updated successfully.');
        } else {
            $this->user = User::create($data);
            session()->flash('message', 'User created successfully.');
        }

        return redirect()->route('users.index');
    }

    private function rules(): array
    {
        $emailRule = Rule::unique('users', 'email');
        if ($this->user) {
            $emailRule = $emailRule->ignore($this->user->id);
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', $emailRule],
            'role' => ['required', Rule::in(['admin', 'supervisor', 'head', 'staff'])],
            'password' => [$this->user ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function render()
    {
        return view('livewire.users.edit')->layout('components.layouts.app');
    }
}
