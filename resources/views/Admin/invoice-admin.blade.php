<x-app-admin>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Invoices Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-10xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg" style="padding: 3%;">
                <h1>Invoices</h1>
                <livewire:invoice-table />

                @push('js-livewire')
                <script>
                    Livewire.on('swal', function(data) {

                        Swal.fire({
                        text: data[0].title,
                        icon: data[0].icon,
                        confirmButtonText: 'Aceptar',
                        timer:data[0].timer, // en milisegundos // Tiempo en milisegundos (5 segundos)
                        timerProgressBar: true, // Muestra la barra de progreso del tiempo
                        });


                    });




                </script>
                @endpush
            </div>
        </div>
    </div>
</x-app-admin>