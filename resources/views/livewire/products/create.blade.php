<div wire:ignore.self id="medium-modal" tabindex="-1"
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full  justify-center items-center flex">
    <div class="relative w-full max-w-3xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 border-b rounded-t md:p-5 dark:border-gray-600">
                <h3 class="text-3xl font-medium text-gray-900 dark:text-white">
                    Sell an item
                </h3>
                <button id="modal-cross" type="button"
                    class="inline-flex items-center justify-center w-8 h-8 text-sm text-gray-400 bg-transparent rounded-lg hover:bg-gray-200 hover:text-gray-900 ms-auto dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="medium-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-4 space-y-4 md:p-5">
                <form wire:submit.prevent="submit" class="space-y-6">
                    {{-- <div>
                        <label for="photos" class="block text-sm font-medium text-gray-700">Upload photos</label>
                        <div class="flex justify-center px-6 py-12 mt-1 border-gray-300 rounded-md"
                            style="border-width:1px">
                            <div class="space-y-1 text-center">
                                <label for="file-upload"
                                    class="relative inline-flex items-center justify-center px-4 py-2 bg-white border rounded-md cursor-pointer focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500"
                                    style="border-color: #D9F99D">
                                    <span>Upload photo</span>
                                    <input id="file-upload" wire:model="form.photo" name="file-upload" type="file"
                                        class="sr-only">
                                </label>
                                @if ($form->photo)
                                <p class="mt-2 text-sm text-gray-500">{{ $form->photo->getClientOriginalName() }}</p>
                                @endif
                            </div>
                        </div>
                        @error('form.photo') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div> --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" wire:model="form.name" name="name" id="name"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm sm:text-sm">
                        @error('form.name') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Describe your
                            item</label>
                        <textarea id="description" wire:model="form.description" name="description" rows="4"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm sm:text-sm"></textarea>
                        @error('form.description') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                        <select id="category" wire:model="form.category" name="category"
                            class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                            <option value="" selected disabled>Select</option>
                            @foreach ($form->categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('form.category') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Item price</label>
                        <div class="relative mt-1 rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">£</span>
                            </div>
                            <input type="text" wire:model="form.price" name="price" id="price"
                                class="block w-full text-right border-gray-300 rounded-md pl-7 pr-7 sm:text-sm"
                                placeholder="00.00">
                        </div>
                        @error('form.price') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-center border-gray-200 rounded-b dark:border-gray-600">
                        <button type="submit" class="w-full px-4 py-2 rounded" style="background-color: #D9F99D;">Upload
                            item
                            <i wire:loading wire:target="submit" class="fas fa-spinner fa-spin"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>