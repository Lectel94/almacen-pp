<?php

namespace App\Livewire;

use App\Models\Coupon;
use Livewire\Component;

class AddCoupon extends Component
{
    public $code;
    public $discount_amount = 0;
    public $is_percentage = false;
    public $expires_at;

    protected $rules = [
        'code' => 'required|string|max:255',
        'discount_amount' => 'required|numeric|min:0',
        'is_percentage' => 'required|boolean',
        'expires_at' => 'required|date',
    ];



    public function createVariant()
    {
        $this->validate();


        Coupon::create([
            'code' => $this->code,
            'discount_amount' => $this->discount_amount,
            'is_percentage' => $this->is_percentage,
            'expires_at' => $this->expires_at,
        ]);

        // Opcional: evento para notificar o limpiar el formulario
        $this->dispatch('couponAdded');

        // Limpiar campo
        $this->reset(['code','discount_amount','is_percentage','expires_at']);
        $this->dispatch('close-modal');

        // Opcional: emitir evento para actualizar la tabla
        // Si quieres que se actualice automáticamente, en el componente PowerGrid escucha ese evento.
        // Pero PowerGrid suele detectar cambios en la fuente de datos, así que no es obligatorio.
    }
    public function render()
    {
        return view('livewire.add-coupon');
    }
}
