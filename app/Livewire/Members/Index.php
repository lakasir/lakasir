<?php

namespace App\Livewire\Members;

use App\Models\Tenants\Member;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

#[Layout('layouts.app', ['title' => 'Pelanggan - Lakasir POS', 'header' => 'Data Pelanggan (Member)'])]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    
    // Form Properties
    public $showModal = false;
    public $isEdit = false;
    public $memberId = null;
    
    // Member fields
    public $name = '';
    public $code = '';
    public $email = '';
    public $address = '';
    public $identity_type = '';
    public $identity_number = '';
    public $joined_date = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->code = 'MBR-' . strtoupper(Str::random(6)); // Auto-generate code
        $this->joined_date = now()->format('Y-m-d');
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetForm();
        $member = Member::findOrFail($id);
        $this->memberId = $member->id;
        
        $this->name = $member->name;
        $this->code = $member->code;
        $this->email = $member->email;
        $this->address = $member->address;
        $this->identity_type = $member->identity_type;
        $this->identity_number = $member->identity_number;
        $this->joined_date = $member->joined_date ? \Carbon\Carbon::parse($member->joined_date)->format('Y-m-d') : '';
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:members,code,' . $this->memberId,
            'email' => 'nullable|email|max:255|unique:members,email,' . $this->memberId,
            'address' => 'nullable|string|max:500',
            'identity_type' => 'nullable|string|max:50',
            'identity_number' => 'nullable|string|max:100',
            'joined_date' => 'nullable|date',
        ]);

        $data = [
            'name' => $this->name,
            'code' => $this->code,
            'email' => $this->email,
            'address' => $this->address,
            'identity_type' => $this->identity_type,
            'identity_number' => $this->identity_number,
            'joined_date' => $this->joined_date,
        ];

        if ($this->isEdit) {
            $member = Member::findOrFail($this->memberId);
            $member->update($data);
        } else {
            Member::create($data);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete($id)
    {
        $member = Member::findOrFail($id);
        $member->delete();
    }

    public function resetForm()
    {
        $this->memberId = null;
        $this->name = '';
        $this->code = '';
        $this->email = '';
        $this->address = '';
        $this->identity_type = '';
        $this->identity_number = '';
        $this->joined_date = '';
        $this->resetValidation();
    }

    public function render()
    {
        $members = Member::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('code', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.members.index', [
            'members' => $members,
        ]);
    }
}
