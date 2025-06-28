@props(['order'])

<div class="w-full max-w-sm mx-auto" x-data="{ open: false }">
    <!-- Botón para abrir el modal -->
    <button @click="open = true" class="px-4 py-2 font-semibold text-white bg-blue-600 rounded hover:bg-blue-700">
        Ver detalles
    </button>

    <!-- Modal con Alpine -->
    <div x-show="open" style="display: none;" @keydown.escape.window="open = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <!-- Contenedor del modal para centrar -->
        <div @click.away="open = false"
            class="w-full max-w-3xl max-h-full p-4 mx-4 overflow-y-auto bg-white rounded-lg shadow-xl sm:mx-6 md:mx-8 lg:mx-12">
            <!-- Encabezado -->
            <div class="flex items-center justify-between mb-4">
                <h5 class="text-xl font-semibold text-gray-800">Detalles de la Orden #{{ $order->number }}</h5>
                <button @click="open = false" class="text-gray-600 hover:text-gray-800">&times;</button>
            </div>

            <!-- Contenido -->
            <div class="space-y-4">
                {{-- Información de la orden --}}
                <div>
                    <h6 class="mb-2 text-lg font-semibold">Información de la Orden</h6>
                    <p><strong>Número:</strong> {{ $order->number }}</p>
                    <p><strong>Nombre:</strong> {{ $order->first_name }} {{ $order->last_name }}</p>
                    <p><strong>Empresa:</strong> {{ $order->company_name ?? 'N/A' }}</p>
                    <p><strong>Dirección:</strong> {{ $order->address }}</p>
                    <p><strong>Ciudad:</strong> {{ $order->city }}</p>
                    <p><strong>País:</strong> {{ $order->country }}</p>
                    <p><strong>Código Postal:</strong> {{ $order->postcode }}</p>
                    <p><strong>Teléfono:</strong> {{ $order->mobile }}</p>
                    <p><strong>Email:</strong> {{ $order->email }}</p>
                    <p><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>
                    <p><strong>Estado:</strong> {{ $order->status }}</p>
                </div>

                {{-- Detalles productos --}}
                <div>
                    <h6 class="mb-2 text-lg font-semibold">Detalles del Producto</h6>
                    @if($order->orderDetails->isEmpty())
                    <p>No hay detalles para esta orden.</p>
                    @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-300 divide-y divide-gray-200 rounded-lg">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th
                                        class="px-2 py-1 text-sm font-medium text-left text-gray-700 border border-gray-300">
                                        Image</th>
                                    <th <th
                                        class="px-2 py-1 text-sm font-medium text-left text-gray-700 border border-gray-300">
                                        Producto</th>
                                    <th
                                        class="px-2 py-1 text-sm font-medium text-left text-gray-700 border border-gray-300">
                                        Cantidad</th>
                                    <th
                                        class="px-2 py-1 text-sm font-medium text-right text-gray-700 border border-gray-300">
                                        Precio</th>
                                    <th
                                        class="px-2 py-1 text-sm font-medium text-right text-gray-700 border border-gray-300">
                                        Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($order->orderDetails as $detail)
                                <tr>
                                    <td class="flex items-center justify-center px-2 py-1 border border-gray-300">

                                        <img src="{{ asset( $detail->product->image_url ? '/storage/'.$detail->product->image_url : '/img/logo1.jpg' )}}"
                                            alt="{{ $detail->product->name }}" class="object-cover w-12 h-12 rounded" />

                                    </td>
                                    <td class="px-2 py-1 border border-gray-300">{{ $detail->product->name ?? 'Producto
                                        eliminado' }}</td>
                                    <td class="px-2 py-1 border border-gray-300">{{ $detail->quantity }}</td>
                                    <td class="px-2 py-1 text-right border border-gray-300">${{
                                        number_format($detail->unit_price, 2) }}</td>
                                    <td class="px-2 py-1 text-right border border-gray-300">${{
                                        number_format($detail->total_price, 2) }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td class="px-2 py-1 border border-gray-300" colspan="3"
                                        class="text-lg font-semibold">Total General:</td>
                                    <td class="px-2 py-1 font-semibold text-right border border-gray-300">${{
                                        number_format($order->total, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Pie del modal -->
            <div class="flex justify-end mt-4">
                <button @click="open = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>