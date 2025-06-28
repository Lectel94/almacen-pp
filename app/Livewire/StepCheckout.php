<?php

namespace App\Livewire;

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

    public function mount()
    {
        $this->loadCart();
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
                'unit_price' => $item['product']->list_price,
                'total_price' => $item['product']->list_price * $item['quantity'],
            ]);
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
        $total = 0;
        foreach ($cartItems as $item) {

            $total += $item['product']->list_price * $item['quantity'];
        }

        $this->total=$total;
        $this->subtotal=$total;
        return view('livewire.step-checkout', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }
}

