<div>
    <div class="py-12">
        <div class="mx-auto max-w-9xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">


                <div class="container mx-auto lg:px-24" style="padding-top: 20px;">

                    {{-- <div class="px-4 py-2 text-center rounded mb-7" style="background-color: #D9F99D;">
                        <h1 class="text-xl font-bold text-gray-900">Demo E-Commerce Experience Powered by TALL
                            (Tailwind, Alpine,
                            Laravel, Livewire)</h1>
                        <p class="mt-2 text-sm text-gray-600">This is just a demo page developed by <a
                                href="https://github.com/oldravian" target="_blank"
                                class="text-blue-500 underline">Habib</a> to
                            demonstrate his TALL stack skills.</p>
                    </div> --}}
                    <header class="flex flex-col items-center px-8 py-4 bg-white lg:flex-row lg:justify-between">
                        <div class="w-full mb-4 lg:w-1/2 lg:mb-0">
                            @livewire('products.search')
                        </div>

                        <div class="flex flex-col items-center justify-end w-full lg:flex-row lg:w-1/2 lg:space-x-4">
                            @livewire('products.sort')

                        </div>
                    </header>

                    <main class="px-8 py-8">
                        <div>
                            @if($products->count() > 0)
                            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                                @foreach($products as $product)
                                @livewire('products.product-card', ['product' => $product], key($product->id))
                                @endforeach
                            </div>
                            @else
                            <div class="flex items-center justify-center h-64">
                                <div class="text-center">
                                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"></path>
                                    </svg>
                                    <p class="mt-4 text-lg font-semibold text-gray-600">No products found</p>
                                    <p class="mt-2 text-gray-500">Try adjusting your search or filter to find what
                                        you're looking
                                        for.</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </main>

                    @if($products->count() > 0)
                    <div class="p-5 mt-5 border border-gray-300 rounded bg-gray-50" style="margin-bottom: 25px;">
                        <!-- Pagination Section -->
                        {{ $products->links() }}
                    </div>




                    @endif
                </div>
            </div>
        </div>
    </div>


</div>