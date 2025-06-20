<div
    class="flex items-center justify-between max-w-2xl px-4 py-3 mx-auto space-x-2 bg-white border rounded-md shadow-sm">
    <!-- Botón anterior -->
    <button wire:click.prevent="previousPage"
        class="flex items-center px-3 py-1 text-gray-600 bg-gray-100 rounded hover:bg-gray-200">
        <!-- Icono de flecha izquierda -->
        @include('components.svgs.previous')
        <span class="ml-2">Anterior</span>
    </button>

    <!-- Números de página, en línea y con flechas -->
    <div class="flex space-x-2 overflow-x-auto">
        @for ($page = 1; $page <= $lastPage; $page++) <button wire:click.prevent="goToPage({{ $page }})"
            class="px-3 py-1 min-w-[40px] text-center border rounded {{ $currentPage == $page ? 'border-green-500 font-bold' : 'border-gray-300' }} hover:bg-gray-200">
            {{ $page }}
            </button>
            @endfor
    </div>

    <!-- Botón siguiente -->
    <button wire:click.prevent="nextPage"
        class="flex items-center px-3 py-1 text-gray-600 bg-gray-100 rounded hover:bg-gray-200">
        <span class="mr-2">Siguiente</span>
        <!-- Icono de flecha derecha -->
        @include('components.svgs.next')
    </button>
</div>