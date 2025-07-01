<?php

namespace App\Livewire;

use App\Models\Coupon;
use App\Models\Product;
use Livewire\Component;

class StepCart extends Component
{
    public $cartItemsAux = [];
    public $subtotal = 0;
    public $shippingCost = 0;
    public $total = 0;
    public $shippingDestination = 'pendiente'; // o directamente el país

    public $couponCode;
    public $discountAmount = 0;

    public $appliedCoupon = null;

    public function mount()
    {
        $this->loadCart();
        $this->validateCartStock();

        // Cargar coupon desde sesión si existe
        $this->loadCouponFromSession();

        // Calcula subtotal y total
        $this->calculateSubtotal();
        $this->calculateTotal();
    }

    public function validateCartStock()
        {
            $messages = [];

            foreach ($this->cartItemsAux as $item) {
                $product = $item['product'];
                $quantity = $item['quantity'];
                if ($product->stock < $quantity) {
                    $messages[] = "El producto {$product->name} solo tiene {$product->stock} unidades disponibles.";
                }
            }


            if (count($messages) > 0) {
                $this->dispatch('stock-problem', [
                    'messages' => $messages,
                ]);
            }
        }


        public function proceedToCheckout()
            {
                // Validar stock aquí
                $cart = session()->get('cart', []);
                $messages = [];
                $canProceed = true;

                foreach ($cart as $item) {
                    $product = Product::find($item['product_id']);
                    if ($product && $product->stock < $item['quantity']) {
                        $canProceed = false;
                        $messages[] = "El producto {$product->name} solo tiene {$product->stock} unidades disponibles.";
                    }
                }

                if ($canProceed) {
                    // Redireccionar a la vista de checkout
                    return redirect()->route('step-checkout');
                } else {
                    // Mostrar mensaje en frontend
                    // Puedes usar eine propiedad Livewire o evento
                    $this->dispatch('stock-problem', [
                        'messages' => $messages,
                    ]);
                }
            }

    // Carga carrito desde sesión
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

    // Guarda carrito en sesión
    public function saveCart()
    {
        $cartToSave = [];
        foreach ($this->cartItemsAux as $productId => $item) {
            $cartToSave[] = [
                'product_id' => $productId,
                'quantity' => $item['quantity'],
            ];
        }
        session()->put('cart', $cartToSave);
    }

    // Carga el cupón desde la sesión
    public function loadCouponFromSession()
    {
        $couponData = session()->get('coupon_discount');
        if ($couponData) {
            $this->appliedCoupon = (object) [
                'code' => $couponData['code'],
                'is_percentage' => $couponData['is_percentage'],
            ];
            $this->discountAmount = $couponData['amount'];
        } else {
            $this->appliedCoupon = null;
            $this->discountAmount = 0;
        }
    }

    // Aplica un cupón
    public function applyCoupon()
    {
        $coupon = Coupon::where('code', trim($this->couponCode))
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>=', now());
            })->first();

        if ($coupon) {
            $this->appliedCoupon = $coupon;

            if ($coupon->is_percentage) {
                $this->discountAmount = ($this->subtotal * $coupon->discount_amount) / 100;
            } else {
                $this->discountAmount = $coupon->discount_amount;
            }

            // Guardar en sesión
            session()->put('coupon_discount', [
                'code' => $coupon->code,
                'amount' => $this->discountAmount,
                'is_percentage' => $coupon->is_percentage,
            ]);
        } else {
            session()->flash('error', 'Código de cupón inválido o expirado.');
            $this->discountAmount = 0;
            $this->appliedCoupon = null;
            session()->forget('coupon_discount');
        }
        $this->calculateTotal(); // actualiza total
    }

    // Elimina cupón
    public function removeCoupon()
    {
        $this->couponCode = '';
        $this->discountAmount = 0;
        $this->appliedCoupon = null;
        session()->forget('coupon_discount');
        $this->calculateTotal(); // actualiza total
    }

    // Calcula subtotal
    public function calculateSubtotal()
    {
        $total = 0;
        foreach ($this->cartItemsAux as $item) {
            $total += $item['product']->precio_por_rol * $item['quantity'];
        }
        $this->subtotal = $total;
    }

    // Calcula total final
    public function calculateTotal()
    {
        $this->total = max(0, $this->subtotal + $this->shippingCost - $this->discountAmount);
    }

    // Eliminar del carrito
    public function removeFromCart($productId)
    {
        unset($this->cartItemsAux[$productId]);
        $this->saveCart();
        $this->loadCart();
        $this->calculateSubtotal();
        $this->calculateTotal();
        $this->dispatch('updateCart');
    }

    // Vaciar carrito completo
    public function clearCart()
    {
        $this->reset('cartItemsAux');
        $this->saveCart();
        $this->loadCart();
        $this->calculateSubtotal();
        $this->calculateTotal();
        $this->dispatch('updateCart');
    }

    public function updateQuantity($productId, $quantity)
    {

        $product=Product::find($productId);
        if ($quantity <= 0) {
            $this->removeFromCart($productId);
            return;
        }

        if ($product->stock >= $quantity) {
            $cartSession = session()->get('cart', []);
            foreach ($cartSession as &$item) {
                if ($item['product_id'] == $productId) {
                    $item['quantity'] = $quantity;
                    break;
                }
            }
            session()->put('cart', $cartSession);
            $this->loadCart(); // recarga los datos del carrito
            $this->calculateSubtotal();
            $this->calculateTotal();
            $this->dispatch('updateCart');
        }else{

            $cartSession = session()->get('cart', []);
            foreach ($cartSession as &$item) {
                if ($item['product_id'] == $product->id) {
                    $item['quantity'] = $product->stock;
                    break;
                }
            }
            session()->put('cart', $cartSession);
            $this->loadCart(); // recarga los datos del carrito
            $this->calculateSubtotal();
            $this->calculateTotal();
            $this->dispatch('updateCart');

            $this->dispatch('swal', [
                'title' => trans('Cantidad no disponible en Stock'),
                'icon' => 'warning',
                'timer' => 4000,
                'quantity' => $product->stock,
                'productId' => $product->id,
            ]);
        }


    }

// Método render
    public function render()
    {
        /* $this->validateCartStock(); */
        // Cargar el cupón desde sesión si existe
        $this->loadCouponFromSession();

        // Cargar carrito
        $this->loadCart();

        // Calcular subtotal y totales
        $this->calculateSubtotal();
        $this->calculateTotal();

        return view('livewire.step-cart',['cartItems'=>$this->cartItemsAux]);
    }

}
