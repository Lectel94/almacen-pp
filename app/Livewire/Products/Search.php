<?php

namespace App\Livewire\Products;

use Livewire\Component;

class Search extends Component
{
    public $search = '';

    protected $listeners = [
        'reset_search' => 'reset_search'
       ];

    public function updatedSearch()
    {
        $this->dispatch('searchUpdated', $this->search);
    }

    public function reset_search()
    {
        $this->reset('search');
    }



    public function render()
    {
        return view('livewire.products.search');
    }
}
