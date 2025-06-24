<div x-data="{ open: @entangle('show') }" x-show="open"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;"
    @keydown.escape.window="open = false" @click.away="open = false">

    <div class="relative w-full max-w-xl p-4 bg-white rounded shadow-lg">

        <!-- Botón para cerrar -->
        <button @click="open = false" class="absolute text-gray-600 top-2 right-2 hover:text-gray-800"
            aria-label="Cerrar">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </button>

        @if ($product)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="relative flex flex-col w-full max-w-3xl p-4 bg-white rounded shadow-lg md:flex-row">
                <!-- Imagen -->
                <div class="flex-shrink-0 md:w-1/2">
                    <img class="object-cover w-full h-48 md:h-full"
                        src="{{ asset($product->image_url ? 'storage/' .$product->image_url : '/img/logo1.jpg') }}"
                        alt="{{ $product->name }}">
                </div>
                <!-- Contenido del producto -->
                <div class="w-full mt-4 md:mt-0 md:ml-4 md:w-1/2">
                    <!-- Botón para cerrar -->
                    <button @click="open = false" class="absolute text-gray-600 top-2 right-2 hover:text-gray-800"
                        aria-label="Cerrar">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <!-- Resto del contenido -->
                    <h2 class="mb-2 text-lg font-semibold text-gray-700">{{ $product->name }}</h2>
                    <p class="mb-4 text-sm text-gray-600">{{ $product->description }}Descripción larga del producto...
                    </p>
                    <div class="flex items-center justify-between">
                        <div class="text-xl font-bold text-green-600">${{ number_format($product->list_price, 2) }}
                        </div>
                    </div>

                    <!-- Selector de cantidad (opcional) -->
                    <div x-data="{ quantity: 1 }" class="flex items-center mt-4 space-x-2">


                        <input type="number" wire:model="quantity"
                            class="w-16 text-center border border-gray-300 rounded" min="1" value="1">

                    </div>

                    <!-- Botón agregar -->
                    <button wire:click="addProductById({{ $product->id }})" wire:loading.attr="disabled"
                        wire:target="addProductById({{ $product->id }})" wire:key="add-to-cart-{{ $product->id }}"
                        class="flex items-center justify-center w-full px-4 py-2 mt-4 text-white bg-green-600 rounded hover:bg-green-700">


                        <!-- Mostrar cuando NO está cargando -->
                        <div wire:loading.remove wire:target="addProductById({{ $product->id }})">
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13v6a2 2 0 002 2h8a2 2 0 002-2v-6" />
                                </svg>
                                Añadir al carrito

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

                            </div>
                        </div>



                    </button>
                </div>
            </div>
        </div>
        @else
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="p-4 bg-white rounded shadow-lg">
                Cargando...
            </div>
        </div>
        @endif
    </div>
</div>