<div>
    <!-- Botón del carrito -->
    <div x-data>
        <button @click="$dispatch('toggle-cart')" class="relative p-2 text-black bg-white rounded" title="Ver carrito">
            🛒
            <span class="absolute top-0 right-0 px-1 text-xs text-white bg-green-500 rounded-full">
                ({{ count($cartItems ?? []) }})
            </span>
        </button>
    </div>
</div>