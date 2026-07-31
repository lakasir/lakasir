<?php

namespace App\Livewire\Tables;

use App\Models\Tenants\Table;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app', ['title' => 'Meja - Lakasir POS', 'header' => 'Manajemen Meja (Dine-in)'])]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    
    // Form Properties
    public $showModal = false;
    public $isEdit = false;
    public $tableId = null;
    
    // Fields
    public $number = '';

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
        $table = Table::findOrFail($id);
        $this->tableId = $table->id;
        
        $this->number = $table->number;
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'number' => 'required|string|max:255',
        ]);

        $data = [
            'number' => $this->number,
        ];

        if ($this->isEdit) {
            $table = Table::findOrFail($this->tableId);
            $table->update($data);
        } else {
            Table::create($data);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete($id)
    {
        $table = Table::findOrFail($id);
        $table->delete();
    }

    public function resetForm()
    {
        $this->tableId = null;
        $this->number = '';
        $this->resetValidation();
    }

    public function render()
    {
        $tables = Table::where('number', 'like', '%' . $this->search . '%')
            ->orderBy('number', 'asc') // Usually you want tables ordered by number
            ->paginate(15);

        return view('livewire.tables.index', [
            'tables' => $tables,
        ]);
    }
}
