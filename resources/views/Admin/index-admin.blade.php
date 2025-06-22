<x-app-admin>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{-- {{ __('Index Admin') }} --}}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-10xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg p-[3%]">

                <!-- Título principal -->
                <h1 class="mb-4 text-3xl font-bold"> {{ trans('admin.general.admin_panel') }} </h1>

                <!-- Sección de estadísticas y gráficos -->
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="mt-8 mb-4 text-2xl font-medium text-gray-900">
                        Estadísticas y gráficos
                    </h2>

                    <!-- Grid para los gráficos, en grandes 2 columnas, en menores solo 1 -->
                    <div class="grid grid-cols-1 gap-12 p-0 bg-gray-200 bg-opacity-25 md:grid-cols-1 lg:grid-cols-2">

                        <!-- Gráfico productos -->
                        <div class="p-6 bg-white rounded shadow">
                            <h2 class="mb-6 text-xl font-semibold">Productos por categorías</h2>
                            <canvas id="productosChart"></canvas>
                        </div>

                        <!-- Gráfico usuarios -->
                        <div class="p-6 bg-white rounded shadow">
                            <h2 class="mb-6 text-xl font-semibold">Estadísticas de usuarios</h2>
                            <canvas id="usuariosChart"></canvas>
                        </div>

                    </div>
                </div>



            </div>
        </div>
    </div>


    {{-- <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file" accept=".csv">
        <button type="submit">Subir y cargar CSV</button>
    </form> --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
    // Datos de productos
        const ctxProductos = document.getElementById('productosChart').getContext('2d');

        const labels = @json($productosData['labels']);
        const dataValues = @json($productosData['data']);

        // Función para generar colores dinámicamente
        function generateColors(count) {
        const colors = [];
        const hueStep = Math.floor(360 / count);
        for (let i = 0; i < count; i++) {
            const hue = i * hueStep;
            colors.push(`hsl(${hue}, 70%, 50%)`);
        }
        return colors;
        }

        const backgroundColors = generateColors(labels.length);

        const productosChart = new Chart(ctxProductos, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
            data: dataValues,
            backgroundColor: backgroundColors,
            }]
        },
        options: {
            responsive: true,
            plugins: {
            legend: { position: 'bottom' },
            }
        }
        });

    // Datos de usuarios
    const ctxUsuarios = document.getElementById('usuariosChart').getContext('2d');
    const usuariosChart = new Chart(ctxUsuarios, {
        type: 'bar', // o 'pie' o 'line'
        data: {
            labels: @json($usuariosData['labels']),
            datasets: [{
                label: 'Cantidad',
                data: @json($usuariosData['data']),
                backgroundColor: ['#6366f1', '#f59e0b'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
            }
        }
    });
});
    </script>
</x-app-admin>