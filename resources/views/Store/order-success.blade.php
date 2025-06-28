<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Store2') }}
        </h2>
    </x-slot>
    <!-- Single Page Header start -->
    <div class="py-5 container-fluid page-header" style="padding-top: 0px !important;">
        <h1 class="text-center text-white display-6">Shop</h1>
        <ol class="mb-0 breadcrumb justify-content-center">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Pages</a></li>
            <li class="text-white breadcrumb-item active">Shop</li>
        </ol>
    </div>
    <!-- Single Page Header End -->
    <div class="py-12">

        <div class="mx-auto max-w-10xl sm:px-6 lg:px-12">
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg" style="padding: 3%;">



                <h1 class="alert-info">Su orden con numero LFD-{{ $order->id }} fue creada correctamente</h1>

                <a href="{{ route('my-orders') }}"
                    class="block w-full px-4 py-2 font-semibold text-center text-white bg-green-600 rounded hover:bg-green-700">
                    Mis Órdenes
                </a>


                @push('js-livewire')
                <script>

                </script>
                @endpush
            </div>
        </div>
    </div>
    </x-app-admin>