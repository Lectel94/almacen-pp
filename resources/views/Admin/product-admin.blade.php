<x-app-admin>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Produts Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-10xl sm:px-6 lg:px-12">
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg" style="padding: 2%;">
                <h1>Products</h1>
                <br>
                <br>

                <livewire:add-product />
                <livewire:product-table />
                <livewire:edit-product />







                @push('js-livewire')
                <script>
                    Livewire.on('swal', function(data) {


                        swal({
                            type: data[0].icon,
                            title: data[0].title,
                            showConfirmButton: true,
                            timer: data[0].timer
                        })


                    });
                    // Logo del ejemplo
                    let currentProductId = null;



                    function DellSelect(){
                        Livewire.dispatch('bulkDelete', { tableName: 'product-table-24zj6z-table' });
                    }





                    // Escucha el evento

                        Livewire.on('updateBulkCounter', ({count}) => {
                            document.getElementById('count_check').innerHTML  = count;
                        });



                </script>
                @endpush
            </div>
        </div>
    </div>
</x-app-admin>