<?php

namespace App\Livewire\Users;

use App\Models\Tenants\Role;
use App\Models\Tenants\User;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    
    // Form variables
    public $isEdit = false;
    public $formId = null;
    
    public $name = '';
    public $email = '';
    public $password = '';
    public $is_owner = false;
    public $role = '';
    
    public $roles = [];

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function mount()
    {
        $this->roles = Role::orderBy('name')->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->isEdit = false;
        $this->formId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->is_owner = false;
        $this->role = '';
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'user-modal');
    }

    public function openEditModal($id)
    {
        $this->resetForm();
        $this->isEdit = true;
        $this->formId = $id;

        $user = User::findOrFail($id);
        $this->name = $user->name;
        $this->email = $user->email;
        $this->is_owner = $user->is_owner;
        $this->role = $user->roles->first()?->name ?? '';

        $this->dispatch('open-modal', 'user-modal');
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email' . ($this->isEdit ? ',' . $this->formId : ''),
            'is_owner' => 'boolean',
            'role' => 'nullable|string|exists:roles,name',
        ];

        if (!$this->isEdit || !empty($this->password)) {
            $rules['password'] = 'required|string|min:8';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'is_owner' => $this->is_owner,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        try {
            if ($this->isEdit) {
                $user = User::findOrFail($this->formId);
                $user->update($data);
                
                if ($this->role) {
                    $user->syncRoles([$this->role]);
                } else {
                    $user->syncRoles([]);
                }
                
                $this->dispatch('close-modal', 'user-modal');
                session()->flash('message', 'Karyawan berhasil diperbarui.');
            } else {
                $user = User::create($data);
                
                if ($this->role) {
                    $user->assignRole($this->role);
                }
                
                $this->dispatch('close-modal', 'user-modal');
                session()->flash('message', 'Karyawan berhasil ditambahkan.');
            }
        } catch (Exception $e) {
            session()->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);
            if ($user->id === auth()->id()) {
                session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
                return;
            }
            if ($user->is_owner) {
                session()->flash('error', 'Akun Owner tidak dapat dihapus dari halaman ini.');
                return;
            }
            $user->delete();
            session()->flash('message', 'Karyawan berhasil dihapus.');
        } catch (Exception $e) {
            session()->flash('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        $query = User::query()
            ->with('roles')
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name');

        return view('livewire.users.index', [
            'users' => $query->paginate($this->perPage),
        ])->title('Karyawan & Akses');
    }
}
