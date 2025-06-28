<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Vendor;
use App\Models\Warehouse;

    class EditProduct extends Component
    {
        use WithFileUploads;
         public $showModalEdit = false; // Controlar visibilidad
        public $productId=null;

        public $name, $sku, $barcode, $stock, $list_price, $cost_unit, $total_value, $potencial_revenue, $potencial_profit, $profit_margin, $markup;
        public $warehouse_id, $category_id, $variant_id, $vendor_id;
        public $image_url; // Para carga de imagen

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

        #[\Livewire\Attributes\On('edit-product')]
        public function open($data)
        {

            $this->productId = $data['id'];
            $product = Product::find($data['id']);

            $this->name = $product->name;
            /* $this->image_url = $product->image_url; */
            $this->sku = $product->sku;
            $this->barcode = $product->barcode;
            $this->stock = $product->stock;
            $this->list_price = $product->list_price;
            $this->cost_unit = $product->cost_unit;
            $this->total_value = $product->total_value;
            $this->potencial_revenue = $product->potencial_revenue;
            $this->potencial_profit = $product->potencial_profit;
            $this->profit_margin = $product->profit_margin;
            $this->markup = $product->markup;
            $this->warehouse_id = $product->warehouse_id;
            $this->category_id = $product->category_id;
            $this->variant_id = $product->variant_id;
            $this->vendor_id = $product->vendor_id;

            $this->showModalEdit = true;
        }

    public function close()
    {
        $this->showModalEdit = false;
        $this->reset([
            'name', 'sku', 'barcode', 'stock', 'list_price', 'cost_unit', 'total_value', 'potencial_revenue', 'potencial_profit', 'profit_margin', 'markup', 'warehouse_id', 'category_id', 'variant_id', 'vendor_id'
        ]);
        $this->productId = null;
    }

        protected $rules = [
            'name' => 'required|string|max:255',
            'image_url' => 'nullable|image|max:2048',
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

        public function updateProduct()
        {
            $this->validate();

            $product = Product::find($this->productId);

            $image_path = $product->image_url;

            if ($this->image_url) {
                $image_path = $this->image_url->store('products', 'public');
            }

            $product->update([
                'name' => $this->name,
                'image_url' => $image_path,
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
        // Cerrar modal y resetear datos
        $this->close();
            $this->dispatch('swal', [
                'title' => trans('product.edited'),
                'icon' => 'success',
                'timer' => 3000,
            ]);
        }

        public function render()
        {
            return view('livewire.edit-product', [
                'warehouses' => $this->warehouses,
                'variants' => $this->variants,
                'vendors' => $this->vendors,
                'categories' => $this->categories,
            ]);
        }
    }
