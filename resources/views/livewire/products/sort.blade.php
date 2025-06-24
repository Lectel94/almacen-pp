<div x-data="{ open: false }" class="relative flex items-center inline-block mb-4 space-x-2 text-left lg:mb-0">
    <label for="sort" class="text-gray-700">Sort by</label>
    <button @click="open = !open" @click.away="open = false"
        class="inline-flex justify-between px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded shadow-sm hover:bg-gray-50 focus:outline-none"
        type="button">
        {{ $currentLabel }}
        @include('components.svgs.dropdown')
    </button>
    <div x-show="open" x-transition
        class="absolute right-0 z-50 w-56 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 custom-dropdown"
        role="menu" aria-orientation="vertical" aria-labelledby="sortDropdownButton">
        <div class="py-1" role="none">
            @foreach ($sortOptions as $key => $label)
            <a href="#" wire:click.prevent="setSortOption('{{ $key }}')" @click="open = false"
                class="block px-4 py-2 text-sm text-gray-700" role="menuitem">{{ $label }}</a>
            @endforeach
        </div>
    </div>


</div>