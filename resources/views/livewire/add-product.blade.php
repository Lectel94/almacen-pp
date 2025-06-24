<div>
    <!-- Botón para abrir el modal -->
    <!-- Puedes mantener o quitar este botón si ya lo tienes en otro lugar -->
    <div class="flex justify-end mb-4">
        <button @click="$dispatch('open-modal')"
            class="px-4 py-2 font-semibold text-white bg-gray-500 rounded hover:bg-gray-700">
            New Product
        </button>
    </div>

    <!-- Modal -->
    <div id="add-product-modal" x-data="{ open: false }" @open-modal.window="open = true" x-show="open"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
        <div class="w-full max-w-4xl p-6 bg-white rounded shadow-lg" @click.away="open = false">
            <h2 class="mb-4 text-xl font-bold">Add New Product</h2>
            <form wire:submit.prevent="createProduct" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <!-- Campo Nombre -->
                <div class="col-span-1">
                    <label class="block mb-1 font-semibold">Name</label>
                    <input wire:model="name" type="text" class="w-full p-2 border rounded" />
                    @error('name') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Campo Image-Url -->
                <div class="col-span-1">
                    <label class="block mb-1 font-semibold">Imagen</label>
                    <input wire:model="image_url" type="file" class="w-full p-2 border rounded" />
                    @error('image_url') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>


                <!-- Warehouse -->
                <div class="col-span-1">
                    <label class="block mb-1 font-semibold">Warehouse</label>
                    <select wire:model="warehouse_id" class="w-full p-2 border rounded">
                        <option value="">Select</option>
                        @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                    @error('warehouse_id') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Categoría -->
                <div class="col-span-1">
                    <label class="block mb-1 font-semibold">Category</label>
                    <select wire:model="category_id" class="w-full p-2 border rounded">
                        <option value="">Select</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Variante -->
                <div class="col-span-1">
                    <label class="block mb-1 font-semibold">Variant</label>
                    <select wire:model="variant_id" class="w-full p-2 border rounded">
                        <option value="">Select</option>
                        @foreach($variants as $variant)
                        <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                        @endforeach
                    </select>
                    @error('variant_id') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Vendedor -->
                <div class="col-span-1">
                    <label class="block mb-1 font-semibold">Vendor</label>
                    <select wire:model="vendor_id" class="w-full p-2 border rounded">
                        <option value="">Select</option>
                        @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                    @error('vendor_id') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Campo SKU -->
                <div class="col-span-1">
                    <label class="block mb-1 font-semibold">SKU</label>
                    <input wire:model="sku" type="text" class="w-full p-2 border rounded" />
                    @error('sku') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Campo Barcode -->
                <div class="col-span-1">
                    <label class="block mb-1 font-semibold">Barcode</label>
                    <input wire:model="barcode" type="text" class="w-full p-2 border rounded" />
                </div>

                <!-- Campo Stock -->
                <div class="col-span-1">
                    <label class="block mb-1 font-semibold">Stock</label>
                    <input wire:model="stock" type="number" class="w-full p-2 border rounded" />
                </div>

                <!-- Campo Lista de precio -->
                <div class="col-span-1">
                    <label class="block mb-1 font-semibold">List Price</label>
                    <input wire:model="list_price" type="number" step="0.01" class="w-full p-2 border rounded" />
                </div>

                <!-- Campo Costo por unidad -->
                <div class="col-span-1">
                    <label class="block mb-1 font-semibold">Cost Unit</label>
                    <input wire:model="cost_unit" type="number" step="0.01" class="w-full p-2 border rounded" />
                </div>

                <!-- Campo Valor total -->
                <div class="col-span-1">
                    <label class="block mb-1 font-semibold">Total value</label>
                    <input wire:model="total_value" type="number" step="0.01" class="w-full p-2 border rounded" />
                </div>

                <!-- Potencial Revenue -->
                <div class="col-span-1">
                    <label class="block mb-1 font-semibold">Potencial Revenue</label>
                    <input wire:model="potencial_revenue" type="number" step="0.01" class="w-full p-2 border rounded" />
                </div>

                <!-- Potencial Profit -->
                <div class="col-span-1">
                    <label class="block mb-1 font-semibold">Potencial Profit</label>
                    <input wire:model="potencial_profit" type="number" step="0.01" class="w-full p-2 border rounded" />
                </div>

                <!-- Margen de ganancia -->
                <div class="col-span-1">
                    <label class="block mb-1 font-semibold">Profit Margin (%)</label>
                    <input wire:model="profit_margin" type="number" step="0.01" class="w-full p-2 border rounded" />
                </div>

                <!-- Markup -->
                <div class="col-span-1">
                    <label class="block mb-1 font-semibold">Markup</label>
                    <input wire:model="markup" type="number" step="0.01" class="w-full p-2 border rounded" />
                </div>



                <!-- Botones -->
                <div class="flex justify-end col-span-2 mt-4 space-x-2">
                    <button type="button" @click="open = false" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-white bg-green-600 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

    @push('js-livewire')
    <script>
        // Cerrar modal cuando se emite el evento
        window.addEventListener('close-modal', () => {
            const modal = document.getElementById('add-product-modal');
            if (modal && modal.__x) {
                modal.__x.$data.open = false;
            }
        });
    </script>
    @endpush
</div>