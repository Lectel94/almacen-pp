<div x-data x-init="
    console.log('Alpine iniciado');
    Livewire.on('updateCart', () => {
        console.log('Evento updateCart recibido');
        @this.call('loadCart')
    });
">
    <!-- Botón del carrito con contador -->
    <div x-data>
        <button @click="$dispatch('toggle-cart')" class="relative p-2 text-black bg-white rounded" title="Ver carrito">
            🛒
            <span class="absolute top-0 right-0 px-1 text-xs text-white bg-green-600 rounded-full">
                {{ count($cartItems) }}
            </span>
        </button>
    </div>

    <!-- Modal del carrito -->
    <div x-data="{ open: false }" x-on:toggle-cart.window="open = !open" x-show="open"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50" style="display: none;"
        x-transition>
        <!-- Contenido del carrito -->
        <div class="w-full max-w-3xl max-h-screen p-4 overflow-y-auto bg-white rounded-lg" @click.away="open = false">
            @if(empty($cartItems))
            <p class="text-gray-600">El carrito está vacío.</p>
            @else


            <!-- Botón para vaciar el carrito -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-700">Carrito</h3>
                <button wire:click="clearCart" wire:loading.attr="disabled" wire:target="clearCart()"
                    wire:key="empty-to-cart"
                    class="px-3 py-1 text-sm font-semibold text-white bg-red-500 rounded hover:bg-red-600">

                    <!-- Mostrar cuando NO está eliminando -->
                    <div wire:loading.remove wire:target="clearCart()">
                        <div class="flex items-center justify-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-2 0v1h4V4a1 1 0 00-2 0v3z" />
                            </svg>
                            <span>Vaciar carrito</span>
                        </div>
                    </div>

                    <!-- Mostrar solo cuando se está eliminando en este botón -->
                    <div wire:loading wire:target="clearCart()">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" stroke-width="2"
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


            <ul>
                @foreach($cartItems as $productId => $item)
                <li class="flex items-center justify-between p-2 mb-4 border rounded-lg shadow-sm bg-gray-50">
                    <div class="flex items-center w-full space-x-4">
                        <img src="{{ asset($item['product']->image_url ? 'storage/' .$item['product']->image_url : '/img/logo1.jpg') }}"
                            alt="{{ $item['product']->name }}" class="object-cover w-20 h-20 rounded" />

                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-700">{{ $item['product']->name }}</h4>
                            <p class="text-sm text-gray-500">Precio: ${{ number_format($item['product']->precio_por_rol,
                                2)
                                }}</p>
                            <p class="text-sm text-gray-500">Cantidad: {{ $item['quantity'] }}</p>
                            <p class="text-sm font-semibold text-green-600">
                                Subtotal: ${{ number_format($item['product']->precio_por_rol * $item['quantity'], 2) }}
                            </p>
                        </div>
                    </div>
                    <!-- Botón eliminar -->
                    <div class="ml-4">
                        <button wire:click="removeFromCart({{ $productId }})" wire:loading.attr="disabled"
                            wire:target="removeFromCart({{ $productId }})" wire:key="dell-to-cart-{{ $productId }}"
                            class="text-red-500 hover:text-red-700" title="Eliminar">


                            <!-- Mostrar cuando NO está cargando -->
                            <div wire:loading.remove wire:target="removeFromCart({{ $productId }})">
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- icono basura SVG -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-2 0v1h4V4a1 1 0 00-2 0v3z" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Mostrar solo cuando se está cargando en este botón -->
                            <div wire:loading wire:target="removeFromCart({{ $productId }})">
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- icono basura SVG -->
                                    <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" stroke-width="2"
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
                </li>
                @endforeach
            </ul>

            <div x-data="{ message: '', showMessage: false }">
                <!-- Total y botón -->
                <div class="pt-4 mt-4 border-t">
                    <div class="flex justify-between mb-2 text-lg font-semibold">
                        <span>Total:</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                    <a href="{{ route('step-cart') }}"
                        class="block w-full px-4 py-2 font-semibold text-center text-white bg-green-600 rounded hover:bg-green-700">
                        Finalizar compra
                    </a>
                </div>

                <!-- Mensaje en pantalla -->
                <template x-if="showMessage">
                    <div class="p-4 mt-4 text-yellow-800 bg-yellow-200 rounded shadow" x-text="message"
                        @click="showMessage=false"></div>
                </template>
            </div>
            @endif
        </div>
    </div>
</div>