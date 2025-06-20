<div class="relative overflow-hidden bg-white" style="padding: 5px 10px;">
    <img src="/img/oops.png" alt="Product image" class="object-cover w-full rounded-md h-80">
    <div class="flex items-start justify-between pt-3">
        <div>
            <h2>{{$product->name}}</h2>
            <p class="mb-2 text-lg font-semibold">${{$product->list_price}}</p>
            <div class="flex items-center space-x-2">
                {{-- <img src="/test.jpg" alt="Author" class="w-4 h-4 rounded-full"> --}}
                <p class="text-xs font-normal">@if($product->category)
                    {{$product->category->name}}
                    @endif</p>
            </div>
        </div>

        <div>






            <button class="text-gray-500 focus:outline-none">
                <i class="fas fa-shopping-cart"></i>
            </button>


        </div>
    </div>
</div>