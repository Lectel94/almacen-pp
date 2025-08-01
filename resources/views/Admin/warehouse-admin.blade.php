<x-app-admin>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Warehouses Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-10xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg" style="padding: 3%;">
                <h1>Warehouses</h1>
                <livewire:add-warehouse />

                <livewire:warehouse-table />

                <div id="editModal"
                    class="fixed inset-0 flex items-center justify-center hidden bg-gray-600 bg-opacity-50">
                    <div class="p-4 bg-white rounded">
                        <h2 class="mb-4 text-lg font-semibold">Editar Warehouse</h2>
                        <form {{-- aquí tu formulario --}}>
                            <input type="text" id="editName" class="w-full p-2 mb-4 border" placeholder="Nombre">
                            <button type="button" onclick="saveWarehouse()"
                                class="px-4 py-2 text-white bg-blue-500 rounded">Guardar</button>
                            <button type="button" onclick="closeModal()"
                                class="px-4 py-2 ml-2 bg-gray-300 rounded">Cancelar</button>
                        </form>
                    </div>
                </div>

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
                    // Logo del ejemplo
                    let currentWarehouseId = null;

                    window.addEventListener('edit-warehouse', event => {
                        currentWarehouseId = event.detail.id;
                        document.getElementById('editName').value = event.detail.name;
                        document.getElementById('editModal').classList.remove('hidden');
                    });

                    function saveWarehouse() {
                        const name = document.getElementById('editName').value;
                        // Aquí puedes hacer una llamada AJAX o emitir un evento para guardar
                        alert('Guardando ' + name);
                        closeModal();
                    }

                    function closeModal() {
                        document.getElementById('editModal').classList.add('hidden');
                    }
                </script>
                @endpush
            </div>
        </div>
    </div>
</x-app-admin>