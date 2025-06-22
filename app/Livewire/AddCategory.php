<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;

class AddCategory extends Component
{
    public $name;

    protected $rules = [
        'name' => 'required|string|max:255',
    ];



    public function createCategory()
    {
        $this->validate();


        Category::create([
            'name' => $this->name,
        ]);

        // Opcional: evento para notificar o limpiar el formulario
        $this->dispatch('categoryAdded');

        // Limpiar campo
        $this->reset('name');
        $this->dispatch('close-modal');

        // Opcional: emitir evento para actualizar la tabla
        // Si quieres que se actualice automáticamente, en el componente PowerGrid escucha ese evento.
        // Pero PowerGrid suele detectar cambios en la fuente de datos, así que no es obligatorio.
    }
    public function render()
    {
        return view('livewire.add-category');
    }
}
