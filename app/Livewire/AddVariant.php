<?php

namespace App\Livewire;

use App\Models\Variant;
use Livewire\Component;

class AddVariant extends Component
{
    public $name;

    protected $rules = [
        'name' => 'required|string|max:255',
    ];



    public function createVariant()
    {
        $this->validate();


        Variant::create([
            'name' => $this->name,
        ]);

        // Opcional: evento para notificar o limpiar el formulario
        $this->dispatch('variantAdded');

        // Limpiar campo
        $this->reset('name');
        $this->dispatch('close-modal');

        // Opcional: emitir evento para actualizar la tabla
        // Si quieres que se actualice automáticamente, en el componente PowerGrid escucha ese evento.
        // Pero PowerGrid suele detectar cambios en la fuente de datos, así que no es obligatorio.
    }
    public function render()
    {
        return view('livewire.add-variant');
    }
}
