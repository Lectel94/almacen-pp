<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class StepCart extends Component
{

    public $cartItemsAux = [];
    public $subtotal = 0;
    public $shippingCost = 0;
    public $total = 0;
    public $shippingDestination = 'pendiente'; // o directamente el país

    public function mount()
    {
        $this->loadCart();
    }

    public function removeFromCart($productId)
    {
        unset($this->cartItemsAux[$productId]);
        $this->saveCart();
        $this->loadCart();
        // Emitir evento para que Alpine actualice
        $this->dispatch('updateCart');
    }

    public function clearCart()
    {
        $this->reset('cartItemsAux'); // o la forma que uses para limpiar el carrito
         $this->saveCart();
        $this->loadCart(); // si tienes otro método para recargar
        $this->dispatch('updateCart'); // para actualizar otros componentes si es necesario
    }

    public function loadCart()
    {
        $cartSession = session()->get('cart', []);
        $this->cartItemsAux = [];

        foreach ($cartSession as $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $this->cartItemsAux[$product->id] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                ];
            }
        }
    }

    public function saveCart()
    {
        // Cuando guardes, solo guardas IDs y cantidades
        $cartToSave = [];
        foreach ($this->cartItemsAux as $productId => $item) {
            $cartToSave[] = [
                'product_id' => $productId,
                'quantity' => $item['quantity'],
            ];
        }
        session()->put('cart', $cartToSave);
    }

    public function updateQuantity($productId, $quantity)
    {
        if ($quantity <= 0) {
            $this->removeFromCart($productId);
            return;
        }

        $cartSession = session()->get('cart', []);
        foreach ($cartSession as &$item) {
            if ($item['product_id'] == $productId) {
                $item['quantity'] = $quantity;
                break;
            }
        }
        session()->put('cart', $cartSession);
        $this->loadCart(); // recarga los datos
        $this->dispatch('updateCart');
    }

    public function render()
    {
        // Usar los datos cargados en $cartItemsAux
        $cartItems = $this->cartItemsAux;
        $total = 0;
        foreach ($cartItems as $item) {

            $total += $item['product']->list_price * $item['quantity'];
        }

        $this->total=$total;
        $this->subtotal=$total;

        return view('livewire.step-cart', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }

}
