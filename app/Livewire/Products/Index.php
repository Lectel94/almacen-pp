<?php

namespace App\Livewire\Products;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use App\Models\Product;

use function Laravel\Prompts\progress;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search = '';
    public $sortOption = 'price_asc';
    private $paginationLimit = 10;
    public $currentPage;
    public $lastPage;

    protected $listeners = [
        'productCreated' => 'refreshProducts',
        'searchUpdated' => 'updateSearch',
        'sortUpdated' => 'updateSort',
        'previousPage' => 'goToPreviousPage',
        'nextPage' => 'goToNextPage',

    ];

    public function refreshProducts()
    {
        $this->render();
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

    public function goToPreviousPage()
    {
        $this->previousPage();
    }

    public function goToNextPage()
    {
        $this->nextPage();
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    /* public function gotoPage($page)
    {
        $this->currentPage = $page;
        /* $this->resetPage();
    } */

    private function getQuery()
    {
        $query = Product::with('category:id,name');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('category', function ($query) {
                      $query->where('name', 'like', '%' . $this->search . '%');
                  });
        }

        return $query;
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

    public function deleteProduct(Product $product)
    {
        $product->delete();
        $this->refreshProducts();
    }

    public function render()
    {
        $query = $this->getQuery();
        $sortedQuery = $this->getSortedQuery($query);
        $products = $sortedQuery->paginate($this->paginationLimit)->onEachSide(2);

        /* $this->currentPage = $products->currentPage();
        $this->lastPage = $products->lastPage();

        $this->dispatch('updatePagination', $this->currentPage, $this->lastPage); */

        return view('livewire.products.index', [
            'products' => $products,
            'currentPage' => $this->currentPage,
            'lastPage' => $this->lastPage,
        ]);
    }


}
