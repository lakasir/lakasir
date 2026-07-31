<?php

namespace App\Livewire\Roles;

use App\Models\Tenants\Role;
use Exception;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    
    // Form variables
    public $isEdit = false;
    public $formId = null;
    
    public $name = '';
    public $selectedPermissions = [];

    // All available permissions grouped by first word (e.g., 'create', 'read', 'update', 'delete')
    public $permissions = [];

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function mount()
    {
        // Get all permissions and group them
        $allPermissions = Permission::where('guard_name', 'web')->orderBy('name')->get();
        
        $grouped = [];
        foreach ($allPermissions as $permission) {
            $parts = explode(' ', $permission->name);
            $action = $parts[0];
            $module = implode(' ', array_slice($parts, 1));
            
            if (!isset($grouped[$module])) {
                $grouped[$module] = [];
            }
            $grouped[$module][] = [
                'id' => $permission->name,
                'name' => $permission->name,
                'action' => $action
            ];
        }
        
        $this->permissions = $grouped;
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
        $this->selectedPermissions = [];
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'role-modal');
    }

    public function openEditModal($id)
    {
        $this->resetForm();
        $this->isEdit = true;
        $this->formId = $id;

        $role = Role::findOrFail($id);
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();

        $this->dispatch('open-modal', 'role-modal');
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:roles,name' . ($this->isEdit ? ',' . $this->formId : ''),
            'selectedPermissions' => 'nullable|array',
        ]);

        try {
            if ($this->isEdit) {
                $role = Role::findOrFail($this->formId);
                $role->update(['name' => strtolower($this->name)]);
                $role->syncPermissions($this->selectedPermissions);
                
                $this->dispatch('close-modal', 'role-modal');
                session()->flash('message', 'Role berhasil diperbarui.');
            } else {
                $role = Role::create([
                    'name' => strtolower($this->name),
                    'guard_name' => 'web'
                ]);
                
                if (!empty($this->selectedPermissions)) {
                    $role->syncPermissions($this->selectedPermissions);
                }
                
                $this->dispatch('close-modal', 'role-modal');
                session()->flash('message', 'Role berhasil ditambahkan.');
            }
        } catch (Exception $e) {
            session()->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $role = Role::findOrFail($id);
            if ($role->users()->count() > 0) {
                session()->flash('error', 'Gagal menghapus: Role ini masih digunakan oleh pengguna.');
                return;
            }
            $role->delete();
            session()->flash('message', 'Role berhasil dihapus.');
        } catch (Exception $e) {
            session()->flash('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        $query = Role::query()
            ->withCount('users')
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name');

        return view('livewire.roles.index', [
            'rolesData' => $query->paginate($this->perPage),
        ])->title('Jabatan & Akses (Roles)');
    }
}
