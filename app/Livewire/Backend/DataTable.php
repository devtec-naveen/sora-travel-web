<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Livewire\WithPagination;

class DataTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $loading = false; // loader variable

    protected $paginationTheme = 'bootstrap';

    // --- Reset pagination when search/perPage changes ---
    public function updatingSearch()
    {
        $this->loading = true;
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->loading = false;
    }

    public function updatingPerPage()
    {
        $this->loading = true;
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->loading = false;
    }

    // --- Sorting ---
    public function sortBy($field)
    {
        $this->loading = true;

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
        $this->loading = false;
    }

    // --- Reset all filters ---
    public function resetFilters()
    {
        $this->loading = true;
        $this->reset('search', 'perPage', 'sortField', 'sortDirection');
        $this->resetPage();
        $this->loading = false;
    }
}
