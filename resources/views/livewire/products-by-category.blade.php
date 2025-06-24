<div class="py-5 container-fluid fruite" x-data x-init="
    console.log('Alpine iniciado');
    Livewire.on('updateCart', () => {
        console.log('Evento updateCart recibido');
        @this.call('loadCart')
    });
">


    <div class="container py-6" style="max-width: 1500px">
        {{-- <h1 class="mb-4">Products by Category</h1> --}}
        <div class="row g-4">
            <header class="flex flex-col items-center px-8 py-4 bg-white lg:flex-row lg:justify-between">
                <div class="w-full mb-4 lg:w-1/2 lg:mb-0">
                    @livewire('products.search')
                </div>

                <div class="flex flex-col items-center justify-end w-full lg:flex-row lg:w-1/2 lg:space-x-4">
                    @livewire('products.sort')

                </div>
            </header>
            <!-- Barra de filtros y categorías -->
            <div class="col-lg-2 " x-data="{ open: false }">
                <!-- Botón en móvil para desplegable (desde 768px abajo) -->
                <div class="mb-3 md:hidden">
                    <button @click="open = !open" class="w-full px-4 py-2 text-white bg-green-600 rounded">
                        Categorías &#9662;
                    </button>
                </div>

                <!-- Menú fijo en desktop (desde 768px en adelante) -->
                <div class="hidden mb-3 md:block">
                    <h4 class="mb-2 font-semibold">Categories</h4>
                    <ul class="list-unstyled fruite-categorie">
                        @foreach ($categories as $category)
                        <li class="mb-1">
                            <div class="d-flex justify-content-between fruite-name">
                                <a href="#" wire:click.prevent="selectCategory({{ $category->id }})"
                                    :class="{'bg-blue-100 font-semibold border-blue-300': {{ $category->id }} == {{ $selectedCategory ?? 'null' }} }"
                                    class="block w-full transition rounded hover:bg-blue-100">
                                    <i class="fas fa-apple-alt me-2"></i>{{ $category->name }}
                                </a>
                                <span>({{ count($category->products) }})</span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Menú desplegable en móvil -->
                <div x-show="open" class="md:hidden mb-3 bg-white border rounded shadow max-h-[70vh] overflow-y-auto"
                    style="display: none;">
                    <ul class="p-2 list-unstyled fruite-categorie">
                        @foreach ($categories as $category)
                        <li class="mb-1">
                            <div class="d-flex justify-content-between fruite-name">
                                <a href="#" wire:click.prevent="selectCategory({{ $category->id }})"
                                    @click="open = false"
                                    class="block w-full px-4 py-2 rounded hover:bg-blue-100 transition {{ $category->id == ($selectedCategory ?? 0) ? 'bg-blue-200 font-semibold border-blue-400' : '' }}">
                                    <i class="fas fa-apple-alt me-2"></i>{{ $category->name }}
                                </a>
                                <span>({{ count($category->products) }})</span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <!-- Productos -->
            <div class="col-lg-10 ">
                <div class="row g-4 justify-content-center">
                    @foreach ($products as $product)
                    <div class="col-md-4 col-lg-6 col-xl-3">
                        <!-- Componente de producto con diseño personalizado -->
                        <div class="overflow-hidden bg-white rounded shadow position-relative fruite-item">
                            <div class="fruite-img">
                                <!-- En tu vista del componente A -->
                                <a wire:click="openModal({{ $product->id }})" class="cursor-pointer">
                                    <img src="{{ asset($product->image_url ? 'storage/' .$product->image_url : '/img/logo1.jpg') }}"
                                        alt="{{ $product->name }}" class="img-fluid w-100 rounded-top">
                                </a>

                            </div>
                            <div class="px-3 py-1 text-white rounded bg-success position-absolute"
                                style="top: 10px; left: 10px;">

                                {{ $product->category->name ?? 'Categoría' }}

                            </div>
                            <div class="p-4 rounded-bottom">
                                <a wire:click="openModal({{ $product->id }})" class="cursor-pointer">
                                    <h4 class="mb-2">{{ $product->name }}</h4>
                                </a>
                                <p class="mb-3 text-sm text-gray-600">{{ $product->description }}</p>
                                <div class="d-flex justify-content-between flex-lg-wrap align-items-center">
                                    <p class="mb-0 text-dark fs-5 fw-bold">${{ $product->list_price }}</p>

                                </div>
                                <!-- Selector cantidad -->
                                <div class="mt-2">
                                    <input type="number" min="1" wire:model="quantities.{{ $product->id }}" value="1"
                                        class="w-20 px-2 py-1 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
                                    <button wire:click="addProductById({{ $product->id }})" wire:loading.attr="disabled"
                                        wire:target="addProductById({{ $product->id }})"
                                        wire:key="add-to-cart-{{ $product->id }}"
                                        class="flex-1 px-2 py-1 text-xs text-green-500 bg-gray-200 rounded align-end hover:bg-green-600 hover:text-white"
                                        style="max-width: 50px;float: inline-end">

                                        <!-- Mostrar cuando NO está cargando -->
                                        <div wire:loading.remove wire:target="addProductById({{ $product->id }})">
                                            <div class="flex items-center justify-center space-x-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 7M17 13l1.5 7M6 21h.01M18 21h.01">
                                                    </path>
                                                </svg>

                                            </div>
                                        </div>

                                        <!-- Mostrar solo cuando se está cargando en este botón -->
                                        <div wire:loading wire:target="addProductById({{ $product->id }})">
                                            <div class="flex items-center justify-center space-x-2">
                                                <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor"
                                                    stroke-width="2" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                                </svg>

                                            </div>
                                        </div>

                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <!-- Paginación -->
                <div class="mt-4 d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>