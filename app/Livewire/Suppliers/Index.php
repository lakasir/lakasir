<?php

namespace App\Livewire\Suppliers;

use App\Models\Tenants\Supplier;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app', ['title' => 'Pemasok - Lakasir POS', 'header' => 'Data Pemasok (Supplier)'])]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    
    // Form Properties
    public $showModal = false;
    public $isEdit = false;
    public $supplierId = null;
    
    // Supplier fields
    public $name = '';
    public $phone_number = '';
    public $contact_name = '';
    public $email = '';
    public $address = '';
    public $city = '';
    public $country = '';
    public $postal_code = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetForm();
        $supplier = Supplier::findOrFail($id);
        $this->supplierId = $supplier->id;
        
        $this->name = $supplier->name;
        $this->phone_number = $supplier->phone_number;
        $this->contact_name = $supplier->contact_name;
        $this->email = $supplier->email;
        $this->address = $supplier->address;
        $this->city = $supplier->city;
        $this->country = $supplier->country;
        $this->postal_code = $supplier->postal_code;
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:50',
            'contact_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
        ]);

        $data = [
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'contact_name' => $this->contact_name,
            'email' => $this->email,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'postal_code' => $this->postal_code,
        ];

        if ($this->isEdit) {
            $supplier = Supplier::findOrFail($this->supplierId);
            $supplier->update($data);
        } else {
            Supplier::create($data);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
    }

    public function resetForm()
    {
        $this->supplierId = null;
        $this->name = '';
        $this->phone_number = '';
        $this->contact_name = '';
        $this->email = '';
        $this->address = '';
        $this->city = '';
        $this->country = '';
        $this->postal_code = '';
        $this->resetValidation();
    }

    public function render()
    {
        $suppliers = Supplier::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('contact_name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.suppliers.index', [
            'suppliers' => $suppliers,
        ]);
    }
}
