<x-app-admin>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Orders Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-10xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg" style="padding: 3%;">
                <h1>Orders</h1>
                <livewire:order-table />



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


                    Livewire.on('verif_swal', function(data) {

                        Swal.fire({
                            title: '¿Estás seguro?',
                            text: '¡Esta acción no se puede revertir!',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#ef4444', // color rojo
                            cancelButtonColor: '#6b7280',   // color gris
                            confirmButtonText: 'Sí, eliminar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Aquí ejecutas la acción de eliminación
                                // Ejemplo: emitir a Livewire, hacer una petición fetch, etc.
                                Livewire.dispatch('dell');
                            }
                        });

                    });

                </script>
                @endpush
            </div>
        </div>
    </div>
</x-app-admin>