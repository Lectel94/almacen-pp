<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class ProductCard extends Component
{
   public $quantity = 1;

   public $productId;
    public $product;
    public $show = false;

    protected $listeners = ['open-modal-product' => 'show',
        'addProductById'=>'addProductById',];



    public function mount($productId = null)
    {
        $this->productId = $productId;

    }

    public function addProductById($ip_product){

        $this->dispatch('add-cart-modal',['id'=>$ip_product, 'quantity'=>$this->quantity]);
        $this->reset('quantity');
    }



    public function show($id)
    {
        $this->product = Product::find($id);

        $this->show = true;
    }

    public function close()
    {
        $this->productId =null;
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.product-card');
    }
}
