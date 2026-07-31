<?php

namespace App\Livewire\Categories;

use App\Models\Tenants\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app', ['title' => 'Kategori - Lakasir POS', 'header' => 'Daftar Kategori'])]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    
    // Form Properties
    public $showModal = false;
    public $isEdit = false;
    public $categoryId = null;
    public $name = '';

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
        $category = Category::findOrFail($id);
        $this->categoryId = $category->id;
        $this->name = $category->name;
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.max' => 'Nama kategori maksimal 255 karakter.',
        ]);

        if ($this->isEdit) {
            $category = Category::findOrFail($this->categoryId);
            $category->update([
                'name' => $this->name,
            ]);
        } else {
            Category::create([
                'name' => $this->name,
            ]);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
    }

    public function resetForm()
    {
        $this->categoryId = null;
        $this->name = '';
        $this->resetValidation();
    }

    public function render()
    {
        $categories = Category::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.categories.index', [
            'categories' => $categories,
        ]);
    }
}
