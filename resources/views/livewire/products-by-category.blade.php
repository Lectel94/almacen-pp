<div class="p-4" x-data x-init="
    console.log('Alpine iniciado');
    Livewire.on('updateCart', () => {
        console.log('Evento updateCart recibido');
        @this.call('loadCart')
    });
">
    <!-- Barra superior con categorías -->
    <div class="p-2 mb-4 bg-gray-100 rounded shadow md:flex md:items-center md:justify-between">

        <!-- En móvil un botón desplegable (opcional y ahora en el mismo x-data) -->
        <div class="mb-2 md:hidden" x-data="{ openCategories: false }">
            <!-- botón que usa openCategories -->
            <button @click="openCategories = !openCategories"
                class="px-4 py-2 text-white bg-blue-600 rounded focus:outline-none">
                Categorías &#9662;
            </button>

            <!-- Menú desplegable en móvil -->
            <div x-show="openCategories" class="mb-4 md:hidden">
                <ul class="bg-white border rounded shadow max-h-[70vh] overflow-y-auto">
                    @foreach ($categories as $category)
                    <li class="mb-2">
                        <button wire:click="selectCategory({{ $category->id }})" @click="openCategories = false"
                            class="block w-full text-left px-4 py-2 rounded hover:bg-blue-100 transition duration-200 {{ $category->id == $selectedCategory ? 'bg-blue-200 font-semibold' : '' }}">
                            {{ $category->name }}
                        </button>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Lista de categorías en modo escritorio, ajustada en varias líneas -->
        <div
            class="flex-wrap hidden gap-2 px-4 py-2 bg-gray-100 border-b border-gray-300 rounded-lg shadow-inner md:flex">
            @foreach ($categories as $category)
            <button wire:click="selectCategory({{ $category->id }})" class="px-4 py-2 rounded-full border border-transparent hover:bg-blue-100 transition duration-200 cursor-pointer
               {{ $category->id == $selectedCategory ? 'bg-blue-200 font-semibold border-blue-400' : '' }}">
                {{ $category->name }}
            </button>
            @endforeach
        </div>


    </div>






    {{-- Mostrar categoría seleccionada --}}
    <div class="mb-4">
        <h3 class="text-lg font-semibold text-gray-700">
            Categoría seleccionada:
            <span class="text-blue-700">
                @php
                $categoryName = '';
                if ($selectedCategory) {
                $categoryName = $categories->firstWhere('id', $selectedCategory)->name ?? '';
                }
                @endphp
                {{ $categoryName ?: 'Todos los Productos' }}
            </span>
        </h3>
    </div>

    <header class="flex flex-col items-center px-8 py-4 bg-white lg:flex-row lg:justify-between">
        <div class="w-full mb-4 lg:w-1/2 lg:mb-0">
            @livewire('products.search')
        </div>

        <div class="flex flex-col items-center justify-end w-full lg:flex-row lg:w-1/2 lg:space-x-4">
            @livewire('products.sort')

        </div>
    </header>


    <!-- Sección productos -->
    <div class="w-full p-4 bg-white rounded-lg shadow flex flex-col min-h-[80vh] overflow-y-auto">
        @if ($products && $products->count())
        <h2 class="mb-4 text-xl font-bold text-gray-700">Productos</h2>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
            @foreach ($products as $product)
            <div class="p-2 transition-shadow duration-200 border rounded-lg hover:shadow-lg bg-gray-50">
                <!-- Imagen del producto -->
                <img src="{{ $product->image_url ?? 'https://via.placeholder.com/150' }}" alt="{{ $product->name }}"
                    class="object-cover w-full h-32 mb-2 rounded">

                <!-- Nombre y descripción -->
                <h3 class="mb-1 text-lg font-semibold text-gray-800">{{ $product->name }}</h3>
                <p class="mb-1 text-sm text-gray-600">{{ $product->description }}</p>

                <!-- Precio -->
                <p class="mt-2 text-xl font-bold text-green-600">${{ $product->list_price }}</p>

                <!-- Sección cantidad y botón -->
                <div class="flex items-center mt-2 space-x-2">
                    <!-- Selector de cantidad (por defecto en 1, min 1) -->




                    <input type="number" min="1" wire:model="quantities.{{ $product->id }}" value="1"
                        class="w-12 px-1 py-1 text-xs border rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />

                    <!-- Botón agregar al carrito con icono -->

                    {{-- <button x-data @click="$wire.call('addProductById', {{ $product->id }})"
                        class="w-full px-4 py-2 text-white bg-gray-500 rounded hover:bg-gray-600">

                        <!-- Icono de carrito (usando SVG o FontAwesome) -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 7M17 13l1.5 7M6 21h.01M18 21h.01"></path>
                        </svg>
                    </button> --}}

                    <button wire:click="addProductById({{ $product->id }})" wire:loading.attr="disabled"
                        wire:target="addProductById({{ $product->id }})" wire:key="add-to-cart-{{ $product->id }}"
                        class="flex-1 px-2 py-1 text-xs text-white bg-gray-500 rounded hover:bg-gray-600">

                        <!-- Mostrar cuando NO está cargando -->
                        <div wire:loading.remove wire:target="addProductById({{ $product->id }})">
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 7M17 13l1.5 7M6 21h.01M18 21h.01">
                                    </path>
                                </svg>
                                <span>Agregar al carrito</span>
                            </div>
                        </div>

                        <!-- Mostrar solo cuando se está cargando en este botón -->
                        <div wire:loading wire:target="addProductById({{ $product->id }})">
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                <span>Cargando...</span>
                            </div>
                        </div>

                    </button>
                </div>
            </div>
            @endforeach
        </div>
        <!-- Paginación -->
        <div class="mx-auto mt-6">
            {{ $products->links() }}
        </div>
        @else
        <p class="text-gray-500">Selecciona una categoría para ver productos.</p>
        @endif


    </div>


</div>