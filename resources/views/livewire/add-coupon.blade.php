<div>
    <!-- Botón para abrir el modal -->
    <div class="flex justify-end mb-4">
        <button @click="$dispatch('open-modal')" Livewire.dispatch('initFlatpickr')
            class="px-4 py-2 font-semibold text-white transition duration-200 bg-gray-500 rounded hover:bg-gray-700">
            New Coupon
        </button>
    </div>

    <!-- Modal -->
    <div x-data="{ open: false }" @open-modal.window="open = true" x-show="open"
        class="fixed inset-0 z-50 flex items-center justify-center transition-opacity duration-300 bg-black bg-opacity-50"
        style="display: none;">
        <div class="w-full max-w-lg p-6 transition-transform duration-300 transform bg-white rounded-lg shadow-lg"
            @click.away="open = false" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90">
            <h2 class="mb-4 text-2xl font-semibold text-center text-gray-700">Create Coupon</h2>
            <form wire:submit.prevent="createVariant" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <!-- Código del cupón -->
                <div class="col-span-2">
                    <label class="block mb-1 text-sm font-medium text-gray-700" for="code">Coupon Code</label>
                    <input wire:model="code" type="text" id="code" placeholder="Coupon Code"
                        class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
                    @error('code') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Tipo de descuento: porcentaje o fijo -->
                <div class="flex items-center min-w-full mt-2 space-x-2">
                    <input type="checkbox" wire:model="is_percentage" id="is_percentage" class="accent-blue-600" />
                    <label for="is_percentage" class="text-sm text-gray-700">Is Percentage?</label>
                </div>

                <!-- Monto o porcentaje del descuento -->
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700" for="discount_amount">Discount
                        Amount</label>
                    <input wire:model="discount_amount" type="number" step="0.01" min="0" id="discount_amount"
                        placeholder="e.g., 10 or 20"
                        class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
                    @error('discount_amount') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Fecha y hora de expiración -->
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700" for="expires_at">Expires At</label>
                    <input wire:model="expires_at" type="datetime-local" id="expires_at"
                        class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
                    @error('expires_at') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Botones -->
                <div class="flex justify-end col-span-2 mt-4 space-x-2">
                    <button type="button" @click="open = false"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 text-white transition bg-green-500 rounded hover:bg-green-700">Save</button>
                </div>
            </form>
        </div>
    </div>

</div>