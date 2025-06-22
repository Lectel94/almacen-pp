<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Vendor;
use App\Models\Warehouse;
use Livewire\Component;

class AddProduct extends Component
{
    // Todos los campos del modelo Product como propiedades públicas
    public $name, $sku, $barcode, $stock, $list_price, $cost_unit, $total_value, $potencial_revenue, $potencial_profit, $profit_margin, $markup, $warehouse_id, $category_id, $variant_id, $vendor_id;
    public $warehouses = [];
    public $variants = [];
    public $vendors = [];
    public $categories = [];

    public function mount()
    {
        $this->warehouses = Warehouse::all();
        $this->variants = Variant::all();
        $this->vendors = Vendor::all();
        $this->categories = Category::all();
    }
    protected $rules = [
        'name' => 'required|string|max:255',
        'sku' => 'required|string|max:255',
        'barcode' => 'nullable|string|max:255',
        'stock' => 'required|integer',
        'list_price' => 'required|numeric',
        'cost_unit' => 'required|numeric',
        'total_value' => 'required|numeric',
        'potencial_revenue' => 'nullable|numeric',
        'potencial_profit' => 'nullable|numeric',
        'profit_margin' => 'nullable|numeric',
        'markup' => 'nullable|numeric',
        'warehouse_id' => 'nullable|exists:warehouses,id',
        'category_id' => 'nullable|exists:categories,id',
        'variant_id' => 'nullable|exists:variants,id',
        'vendor_id' => 'nullable|exists:vendors,id',
    ];

    public function createProduct()
    {
        $this->validate();

        Product::create([
            'name' => $this->name,
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'stock' => $this->stock,
            'list_price' => $this->list_price,
            'cost_unit' => $this->cost_unit,
            'total_value' => $this->total_value,
            'potencial_revenue' => $this->potencial_revenue,
            'potencial_profit' => $this->potencial_profit,
            'profit_margin' => $this->profit_margin,
            'markup' => $this->markup,
            'warehouse_id' => $this->warehouse_id,
            'category_id' => $this->category_id,
            'variant_id' => $this->variant_id,
            'vendor_id' => $this->vendor_id,
        ]);

        // Notificar que se agregó el producto
        $this->dispatch('productAdded');

        // Limpiar formulario
        $this->reset([
            'name', 'sku', 'barcode', 'stock', 'list_price', 'cost_unit', 'total_value', 'potencial_revenue', 'potencial_profit', 'profit_margin', 'markup', 'warehouse_id', 'category_id', 'variant_id', 'vendor_id'
        ]);
        $this->dispatch('close-modal');
    }

    public function render()
    {
        return view('livewire.add-product' ,[
        'warehouses' => Warehouse::all(),
        'variants' => Variant::all(),
        'vendors' => Vendor::all(),
        'categories' => Category::all(),
    ]);
    }
}
