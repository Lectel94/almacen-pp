<?php

namespace App\Livewire;

use App\Models\Coupon;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Status;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class StepCheckout extends Component
{



    // Campos del formulario
    public $firstName, $lastName, $companyName, $address, $city, $country, $postcode, $mobile, $email;
    public $notes = '';
   /*  public $createAccount = false; */
    /* public $shipToDifferentAddress = false; */


   /*  public $shippingOptions = [
        'free' => false,
        'flat' => false,
        'local' => false,
    ]; */

   public $cartItemsAux = [];
    public $subtotal = 0;
    public $shippingCost = 0;
    public $total = 0;
    public $shippingDestination = 'pendiente'; // o directamente el país

    // Campos del cupón
    public $couponCode;
    public $discountAmount = 0;
    public $appliedCoupon = null; // Objeto con info del cupón

    public function mount()
    {
        $this->loadCart();
         $this->validateCartStock();
        $this->loadCouponFromSession(); // Cargar cupón desde sesión
        $this->calculateSubtotal();
        $this->calculateTotal();
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
                    $this->submitOrder();
                } else {
                    // Mostrar mensaje en frontend
                    // Puedes usar eine propiedad Livewire o evento
                    $this->dispatch('stock-problem', [
                        'messages' => $messages,
                    ]);
                }
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

    // Método para aplicar un cupón
    public function applyCoupon()
    {
        $coupon = Coupon::where('code', trim($this->couponCode))
            ->where(function($query) {
                $query->whereNull('expires_at')->orWhere('expires_at', '>=', now());
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
        $this->calculateTotal();
    }

    // Método para quitar el cupón
    public function removeCoupon()
    {
        $this->couponCode = '';
        $this->discountAmount = 0;
        $this->appliedCoupon = null;
        session()->forget('coupon_discount');
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = max(0, $this->subtotal + $this->shippingCost - $this->discountAmount);
    }

    public function calculateSubtotal()
    {
        $total = 0;
        foreach ($this->cartItemsAux as $item) {
            $total += $item['product']->precio_por_rol * $item['quantity'];
        }
        $this->subtotal = $total;
    }



    public function submitOrder()
    {


        // Validar datos del formulario
        $this->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'companyName' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postcode' => 'required|string|max:20',
            'mobile' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'notes' => 'nullable|string',
        ]);

        // Crear la orden
        $order = new Order();
        $order->user_id = auth()->user()->id ;

        $order->first_name = $this->firstName;
        $order->last_name = $this->lastName;
        $order->company_name = $this->companyName;
        $order->address = $this->address;
        $order->city = $this->city;
        $order->country = $this->country;
        $order->postcode = $this->postcode;
        $order->mobile = $this->mobile;
        $order->email = $this->email;
        $order->notes = $this->notes;
        $order->total = $this->total;
        $order->status_id = 1; // Puedes establecer otros estados si quieres
        $order->save();
        $order->number =  "LFD-".$order->id;
        $order->save();

        // Guardar los artículos del pedido
        foreach ($this->cartItemsAux as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $item['product']->id,
                'quantity' => $item['quantity'],
                'unit_price' => $item['product']->precio_por_rol,
                'total_price' => $item['product']->precio_por_rol * $item['quantity'],
            ]);
        }

        // Recalcular stocks
        foreach ($this->cartItemsAux as $item) {
            $product = $item['product'];
            $product->stock -= $item['quantity']; // Descarta las unidades compradas
            $product->save();
        }



        // Limpia el carrito
        session()->forget('cart');

        // Opcional: enviar email, mostrar mensaje, redirigir, etc.
        session()->flash('message', '¡Tu pedido ha sido procesado con éxito!');


        $invoice= new Invoice();
          $invoice->order_id = $order->id;
          $invoice->issued_date = date('Y-m-d');
          $invoice->total_amount = $order->total; // o calcula según tus reglas
            $invoice->save();



        // Generar PDF
        $pdf = Pdf::loadView('Invoice.pdf', compact('invoice'));

        // Guardar el PDF en almacenamiento (opcional)
        $pdfPath = 'invoices/invo_'.$order->number.'.pdf';
        Storage::put($pdfPath, $pdf->output());

        // Guardar ruta en la factura si quieres accederla después
        $invoice->pdf_path = $pdfPath;
        $invoice->save();

        // Redireccionar a una página de confirmación o la misma
        return redirect()->route('order-success', ['order' => $order->id]);
    }

   public function render()
    {

        // Usar los datos cargados en $cartItemsAux
        $cartItems = $this->cartItemsAux;



        return view('livewire.step-checkout', [
            'cartItems' => $cartItems,

        ]);
    }
}

