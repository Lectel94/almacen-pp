<div class="p-4 mx-auto max-w-7xl">
    <!-- Barra de búsqueda -->
    {{-- <div class="mb-6">
        <input wire:model.debounce.300ms="search" type="text" placeholder="Buscar órdenes..."
            class="w-full p-3 transition border border-gray-300 rounded-lg shadow-sm md:w-1/3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
    </div> --}}

    @if ($orders->count())

    <!-- Lista de órdenes -->
    <div class="space-y-4">
        @foreach ($orders as $order)
        <!-- Tarjeta de orden -->
        <div class="transition-shadow duration-300 bg-white border border-gray-200 rounded-lg shadow hover:shadow-xl">
            <!-- Encabezado -->
            <div class="flex flex-col p-4 xl:flex-row xl:items-center xl:justify-between " style="align-items: center;">

                <!-- Número -->
                <div class="flex-shrink-0 px-3 py-1 mb-2 font-semibold text-green-600 bg-blue-100 rounded-lg cursor-pointer w-fit xl:mb-0"
                    wire:click="toggleOrder({{ $order->id }})">
                    N°: {{ $order->number }}
                </div>

                <!-- Estado -->
                <div
                    class="flex flex-col items-center justify-center mt-2 xl:mt-0 xl:flex-row xl:items-center xl:space-x-8 xl:text-left">
                    <div
                        class="px-3 py-1 mb-2 font-semibold text-{{$order->status->color}}-600 bg-blue-100 rounded-lg xl:mb-0 w-fit">
                        {{ $order->status->description }}
                    </div>
                    <!-- Total -->
                    <div class="text-sm text-gray-600">
                        Total: <span class="font-semibold">${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>

                <!-- Fecha -->
                <div class="mt-2 text-center xl:mt-0 xl:ml-4">
                    <p class="text-gray-700">Fecha: {{ $order->created_at->format('d/m/Y') }}</p>
                </div>

                <!-- Botón -->
                <div class="mt-2 text-center xl:mt-0 xl:ml-4">
                    <button wire:click="toggleOrder({{ $order->id }})"
                        class="px-3 py-1 text-white transition bg-green-600 rounded-lg hover:bg-green-700">
                        {{ in_array($order->id, $openOrders ?? []) ? 'Ocultar detalles' : 'Ver detalles' }}
                    </button>
                </div>

            </div>

            <!-- Detalles desplegados -->
            @if (in_array($order->id, $openOrders ?? []))
            <div class="p-4 space-y-4 border-t border-gray-200 bg-gray-50">
                <!-- Datos del cliente -->
                <div>
                    <h4 class="mb-2 text-lg font-semibold text-gray-800">Datos del cliente</h4>
                    <p><strong>Nombre:</strong> {{ $order->first_name }} {{ $order->last_name }}</p>
                    <p><strong>Empresa:</strong> {{ $order->company_name }}</p>
                    <p><strong>Dirección:</strong> {{ $order->address }}, {{ $order->city }}, {{
                        $order->country }}, {{ $order->postcode }}</p>
                    <p><strong>Teléfono:</strong> {{ $order->mobile }}</p>
                    <p><strong>Email:</strong> {{ $order->email }}</p>
                    <p><strong>Notas:</strong> {{ $order->notes }}</p>
                </div>

                <!-- Productos -->
                <div>
                    <h4 class="mb-4 text-lg font-semibold text-gray-800">Productos</h4>
                    <div class="space-y-4">
                        @foreach ($order->orderDetails as $item)
                        <!-- Item producto sin tabla, solo usando divs y flex -->
                        <div
                            class="flex flex-col items-center p-2 transition bg-white border border-gray-200 rounded-lg shadow-sm md:flex-row md:justify-between hover:shadow-md">
                            <!-- Imagen -->
                            <div class="flex-shrink-0 w-16 h-16 mr-4">
                                <img src="{{ asset($item->product->image_url ? '/storage/'.$item->product->image_url : '/img/logo1.jpg' ) }}"
                                    alt="{{ $item->product->name }}" class="object-cover w-full h-full rounded-lg">
                            </div>
                            <!-- Datos del producto -->
                            <div
                                class="flex flex-col items-center flex-1 md:flex-row md:justify-between md:items-start">
                                <div class="mb-2 md:mb-0">
                                    <p class="font-semibold">{{ $item->product->name }}</p>
                                </div>
                                <div class="flex flex-col items-center space-y-2 md:flex-row md:space-y-0 md:space-x-4">
                                    <div class="text-sm text-gray-600">Cant: {{ $item->quantity }}</div>
                                    <div class="text-sm text-gray-600">Unit: ${{ number_format($item->unit_price, 2) }}
                                    </div>
                                    <div class="text-sm font-semibold">Total: ${{ number_format($item->total_price, 2)
                                        }}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Paginación -->
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
    @else
    <div class="py-8 text-xl text-center text-gray-600">No hay órdenes para mostrar.</div>
    @endif
</div>

<!-- Templates para alertas y swal (puedes agregar en tu layout) -->
<script>
    window.addEventListener('swal', event => {
        Swal.fire({
            title: event.detail.title,
            icon: event.detail.icon,
            timer: event.detail.timer,
            showConfirmButton: false,
        });
    });

    window.addEventListener('alert', event => {
        Swal.fire({
            icon: event.detail.type,
            text: event.detail.message,
            timer: 2000,
            showConfirmButton: false,
        });
    });
</script>