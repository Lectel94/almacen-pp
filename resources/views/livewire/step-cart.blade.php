<div>
    <!-- Cart Page Start -->
    <div class="py-5 container-fluid">
        <div class="container py-5">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Products</th>
                            <th scope="col">Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                            <th scope="col">Handle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $productId => $item)
                        <tr>
                            <th scope="row">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset($item['product']->image_url ?  '/storage/'.$item['product']->image_url : 'img/logo1.jpg' )}}"
                                        class="img-fluid me-5 rounded-circle" style="width: 80px; height: 80px;" alt="">
                                </div>
                            </th>
                            <td>
                                <p class="mt-4 mb-0">{{ $item['product']->name }}</p>
                            </td>
                            <td>
                                <p class="mt-4 mb-0">{{ number_format($item['product']->list_price, 2) }} $</p>
                            </td>
                            <td>
                                <div class="mt-4 input-group quantity" style="width: 100px;">
                                    <div class="input-group-btn">
                                        <button
                                            wire:click="updateQuantity({{ $productId }}, {{ $item['quantity'] - 1 }})"
                                            class="border btn btn-sm btn-minus rounded-circle bg-light">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <input type="text" class="text-center border-0 form-control form-control-sm"
                                        value="{{ $item['quantity'] }}" readonly>
                                    <div class="input-group-btn">
                                        <button
                                            wire:click="updateQuantity({{ $productId }}, {{ $item['quantity'] + 1 }})"
                                            class="border btn btn-sm btn-plus rounded-circle bg-light">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p class="mt-4 mb-0">{{ number_format($item['product']->list_price * $item['quantity'],
                                    2) }} $</p>
                            </td>
                            <td>
                                <button wire:click="removeFromCart({{ $productId }})"
                                    class="mt-4 border btn btn-md rounded-circle bg-light">
                                    <i class="fa fa-times text-danger"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-5">
                <input type="text" class="py-3 mb-4 border-0 rounded border-bottom me-5" placeholder="Coupon Code">
                <button class="px-4 py-3 btn border-secondary rounded-pill text-primary" type="button">Apply
                    Coupon</button>
            </div>
            <div class="row g-4 justify-content-end">
                <div class="col-8"></div>
                <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                    <div class="rounded bg-light">
                        <div class="p-4">
                            <h1 class="mb-4 display-6">Cart <span class="fw-normal">Total</span></h1>
                            <!-- Subtotal -->
                            <div class="mb-4 d-flex justify-content-between">
                                <h5 class="mb-0 me-4">Subtotal:</h5>
                                <p class="mb-0">{{ number_format($subtotal, 2) }}</p>
                            </div>
                            <!-- Shipping -->
                            <div class="d-flex justify-content-between">
                                <h5 class="mb-0 me-4">Shipping</h5>
                                <div>
                                    <p class="mb-0">Flat rate: {{ number_format($shippingCost, 2) }}</p>
                                </div>
                            </div>
                            <!-- Destino -->
                            <p class="mb-0 text-end">Shipping to {{ $shippingDestination }}.</p>
                        </div>
                        <!-- Total -->
                        <div class="py-4 mb-4 border-top border-bottom d-flex justify-content-between">
                            <h5 class="mb-0 ps-4 me-4">Total</h5>
                            <p class="mb-0 pe-4">{{ number_format($total, 2) }}</p>
                        </div>
                        <!-- Botón -->
                        <a class="px-4 py-3 mb-4 btn border-secondary rounded-pill text-primary text-uppercase ms-4"
                            href="{{ route('step-checkout') }}">
                            Proceed Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cart Page End -->
</div>