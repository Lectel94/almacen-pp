<?php

namespace App\Livewire;

use Livewire\Component;


use Livewire\WithPagination;
use App\Models\Order;

class MyOrders extends Component
{
    use WithPagination;

    public $search = '';

    protected $listeners = ['refreshOrders' => '$refresh'];
    // Agrega esta propiedad en tu clase
    public $openOrders = []; // array para rastrear cuáles órdenes están abiertas

    public function render()
    {
        $orders = Order::query()
                ->where('user_id', auth()->user()->id)

                ->orderBy('created_at', 'desc')
                ->paginate(10);

        return view('livewire.my-orders', compact('orders'));
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }



    // Añade método para toggle
    public function toggleOrder($orderId)
    {
        if (in_array($orderId, $this->openOrders)) {
            // Si ya está abierta, cerrar
            $this->openOrders = array_diff($this->openOrders, [$orderId]);
        } else {
            // Si está cerrada, abrir
            $this->openOrders[] = $orderId;
        }
    }

    public function eliminarOrder($orderId)
    {
        $order = Order::find($orderId);
        if ($order) {
            // Verificar si la orden puede eliminarse (por ejemplo, que no tenga productos asociados)
            if ($order->products()->count() > 0) {
                // Mostrar un mensaje notificando que no puede eliminar
                $this->dispatch('alert', [
                    'type' => 'warning',
                    'message' => 'La orden tiene productos asociados y no puede eliminarse.',
                ]);
                return;
            }

            $order->delete();

            // Mostrar notificación de éxito con swal
            $this->dispatch('swal', [
                'title' => '¡Orden eliminada!',
                'icon' => 'success',
                'timer' => 2000,
            ]);

            // Refrescar la lista
            $this->dispatch('refreshOrders');
        }
    }
}
