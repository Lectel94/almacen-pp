<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsByCategory extends Component
{
    use WithPagination;

    public $categories;
    public $selectedCategory = null;
    public $search = '';
    public $sortOption = 'price_asc';

    public $cartItems = []; // contiene los productos y cantidad
    public $quantities = [];



    protected $paginationTheme = 'tailwind';

    protected $listeners = [
        'addProductById' => 'addProductById',
        'searchUpdated' => 'updateSearch',
        'sortUpdated' => 'updateSort',
        'openModal'=>'openModal',
        'add-cart-modal'=>'add_cart_modal',

    ];



    public function mount()
    {
        $this->categories = Category::all();
        $this->loadCart();
        /* // Inicializa las cantidades en 1 por cada producto
        foreach ($this->products as $product) {
            $this->quantities[$product->id] = 1;
        } */
    }


    public function add_cart_modal($data){
        $productId = $data['id'];
        $quantity = $data['quantity'] ?? 1;
        $product  =Product::find($productId);
        if($product){
            if (isset($this->cartItems[$productId])) {
                        $this->cartItems[$productId]['quantity'] += $quantity;
                    } else {
                        $this->cartItems[$productId] = [
                            'product' => $product,
                            'quantity' => $quantity,
                        ];
                    }

                    $this->saveCart();

                    $this->dispatch('updateCart', count($this->cartItems));
        }

    }
    public function openModal($id){
        $this->dispatch('open-modal-product',$id);
    }
    public function updateSearch($search)
    {
        $this->resetPage();
        $this->search = $search;
    }

    public function updateSort($sortOption)
    {
        $this->resetPage();
        $this->sortOption = $sortOption;
    }

    private function getSortedQuery($query)
    {
        switch ($this->sortOption) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('list_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('list_price', 'desc');
                break;
        }

        return $query;
    }


    public function loadCart()
    {
        $cartSession = session()->get('cart', []);
        $this->cartItems = [];

        foreach ($cartSession as $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $this->cartItems[$product->id] = [
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
        foreach ($this->cartItems as $productId => $item) {
            $cartToSave[] = [
                'product_id' => $productId,
                'quantity' => $item['quantity'],
            ];
        }
        session()->put('cart', $cartToSave);
    }

    public function addProductById($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $this->addToCart($product);
            $this->loadCart();
        }
    }

    public function addToCart($product, $quantity = 1)
    {


        $productId = $product->id;
        $quantity = $this->quantities[$productId] ?? 1;

        if (isset($this->cartItems[$productId])) {
            $this->cartItems[$productId]['quantity'] += $quantity;
        } else {
            $this->cartItems[$productId] = [
                'product' => $product,
                'quantity' => $quantity,
            ];
        }

        $this->saveCart();

        $this->dispatch('updateCart', count($this->cartItems));
    }



    public function removeFromCart($productId)
    {
        unset($this->cartItems[$productId]);
        $this->saveCart();
        $this->loadCart();
        $this->dispatch('updateCart', count($this->cartItems));
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($this->cartItems as $item) {
            $total += $item['product']->list_price * $item['quantity'];
        }
        return $total;
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->reset('search');
         $this->dispatch('reset_search');
        $this->resetPage();

    }

    public function render()
    {


        $productsQuery = Product::query();

        // Filtrar por categoría seleccionada
        if ($this->selectedCategory) {
            $productsQuery->whereHas('category', function ($q) {
                $q->where('id', $this->selectedCategory);
            });
        }

        // Agregar filtro por búsqueda
        if ($this->search) {
            $productsQuery->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->sortOption) {
            $productsQuery = $this->getSortedQuery($productsQuery);
        }



        // Paginar resultados
        $products = $productsQuery->paginate(20);

        return view('livewire.products-by-category', [
            'products' => $products,
            'cartItems' => $this->cartItems,
            'total' => $this->getTotal(),
        ]);
    }
}
