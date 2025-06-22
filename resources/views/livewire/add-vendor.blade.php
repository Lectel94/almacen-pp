<div>
    <!-- Botón para abrir el modal -->
    <div class="flex justify-end mb-4">
        <button @click="$dispatch('open-modal')"
            class="px-4 py-2 font-semibold text-white bg-gray-500 rounded hover:bg-gray-700">
            New Vendor
        </button>
    </div>

    <!-- Modal -->
    <div x-data="{ open: false }" @open-modal.window="open = true" x-show="open"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
        <div class="w-full max-w-md p-6 bg-white rounded shadow-lg" @click.away="open = false">
            <h2 class="mb-4 text-xl">Create Vendor</h2>
            <form wire:submit.prevent="createVendor" class="flex flex-col space-y-2">
                <input wire:model="name" type="text" placeholder="New vendor" class="p-2 border rounded" />
                @error('name') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                <div class="flex justify-end mt-4 space-x-2">
                    <button type="button" @click="open = false"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-white bg-green-500 rounded gray:bg-green-700">Save

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>