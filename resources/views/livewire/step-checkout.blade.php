<div>
    <!-- Checkout Page Start -->
    <div class="py-5 container-fluid">
        <div class="container py-5">
            <h1 class="mb-4">Billing details</h1>
            <form wire:submit.prevent="proceedToCheckout">
                <div class="row g-5">
                    <!-- Datos de facturación -->
                    <div class="col-md-12 col-lg-6 col-xl-7">
                        <div class="row">
                            <div class="col-md-12 col-lg-6">
                                <div class="form-item w-100">
                                    <label class="my-3 form-label">First Name<sup>*</sup></label>
                                    <input type="text" class="form-control" wire:model="firstName">
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6">
                                <div class="form-item w-100">
                                    <label class="my-3 form-label">Last Name<sup>*</sup></label>
                                    <input type="text" class="form-control" wire:model="lastName">
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <label class="my-3 form-label">Company Name<sup>*</sup></label>
                            <input type="text" class="form-control" wire:model="companyName">
                        </div>
                        <div class="form-item">
                            <label class="my-3 form-label">Address <sup>*</sup></label>
                            <input type="text" class="form-control" placeholder="House Number Street Name"
                                wire:model="address">
                        </div>
                        <div class="form-item">
                            <label class="my-3 form-label">Town/City<sup>*</sup></label>
                            <input type="text" class="form-control" wire:model="city">
                        </div>
                        <div class="form-item">
                            <label class="my-3 form-label">Country<sup>*</sup></label>
                            <input type="text" class="form-control" wire:model="country">
                        </div>
                        <div class="form-item">
                            <label class="my-3 form-label">Postcode/Zip<sup>*</sup></label>
                            <input type="text" class="form-control" wire:model="postcode">
                        </div>
                        <div class="form-item">
                            <label class="my-3 form-label">Mobile<sup>*</sup></label>
                            <input type="tel" class="form-control" wire:model="mobile">
                        </div>
                        <div class="form-item">
                            <label class="my-3 form-label">Email Address<sup>*</sup></label>
                            <input type="email" class="form-control" wire:model="email">
                        </div>
                        <div class="form-item">
                            <textarea wire:model="notes" name="text" class="form-control" spellcheck="false" cols="30"
                                rows="11" placeholder="Order Notes (Optional)"></textarea>
                        </div>
                    </div>

                    <!-- Resumen y Totales -->
                    <div class="col-md-12 col-lg-6 col-xl-5">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Products</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $index => $item)
                                    <tr>
                                        <th scope="row">
                                            <div class="mt-2 d-flex align-items-center">
                                                <img src="{{ asset($item['product']->image_url ?  '/storage/'.$item['product']->image_url : 'img/logo1.jpg' )}}"
                                                    class="img-fluid rounded-circle" style="width: 90px; height: 90px;"
                                                    alt="">
                                            </div>
                                        </th>
                                        <td class="py-5">{{ $item['product']->name }}</td>
                                        <td class="py-5">${{ number_format($item['product']->precio_por_rol, 2) }}</td>
                                        <td class="py-5">
                                            <input type="number" wire:model.number="cartItems.{{ $index }}.quantity"
                                                value="{{ $item['quantity'] }}"
                                                data-product-id="{{ $item['product']->id }}"
                                                data-original-quantity="{{ $item['quantity'] }}"
                                                wire:change="updateQuantity({{ $item['product']->id }}, $event.target.value)"
                                                min="1" class="form-control form-control-sm" style="width:80px;">
                                        </td>
                                        <td class="py-5">
                                            ${{ number_format($item['product']->precio_por_rol * $item['quantity'], 2)
                                            }}
                                        </td>
                                    </tr>
                                    @endforeach
                                    <!-- Subtotal -->
                                    <tr>

                                        <td class="py-5"></td>
                                        <td class="py-5"></td>
                                        <td class="py-5">
                                            <p class="py-3 mb-0 text-dark">Subtotal</p>
                                        </td>
                                        <td class="py-5">
                                            <div class="py-3 border-bottom border-top">
                                                <p class="mb-0 text-dark">${{ number_format($subtotal, 2) }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Shipping -->
                                    <tr>

                                        <td class="py-5">
                                            <p class="py-4 mb-0 text-dark">Shipping</p>
                                        </td>
                                        <td colspan="3" class="py-5">
                                            <div class="form-check text-start">

                                                <label class="form-check-label" for="Shipping-1">Pendiente</label>
                                            </div>
                                            {{--
                                            <!-- Aquí puedes agregar opciones de envío si quieres -->
                                            <div class="form-check text-start">
                                                <input type="checkbox" class="border-0 form-check-input bg-primary"
                                                    id="Shipping-1" wire:model="shippingOption" value="free">
                                                <label class="form-check-label" for="Shipping-1">Free Shipping</label>
                                            </div>
                                            <div class="form-check text-start">
                                                <input type="checkbox" class="border-0 form-check-input bg-primary"
                                                    id="Shipping-2" wire:model="shippingOption" value="flat">
                                                <label class="form-check-label" for="Shipping-2">Flat rate:
                                                    $15.00</label>
                                            </div>
                                            <div class="form-check text-start">
                                                <input type="checkbox" class="border-0 form-check-input bg-primary"
                                                    id="Shipping-3" wire:model="shippingOption" value="local">
                                                <label class="form-check-label" for="Shipping-3">Local Pickup:
                                                    $8.00</label>
                                            </div> --}}
                                        </td>
                                    </tr>
                                    <!-- Sección del cupón -->

                                    <tr>

                                        <td class="py-5">
                                            <p class="py-4 mb-0 text-dark">Apply Coupon</p>
                                        </td>
                                        <td colspan="3" class="py-5">
                                            <div class="form-check text-start">
                                                <div class="mb-3 d-flex align-items-center">
                                                    @if(!$appliedCoupon)
                                                    <input type="text" class="form-control"
                                                        placeholder="Enter Coupon Code" wire:model.lazy="couponCode" />
                                                    <i class="cursor-pointer fas fa-paper-plane ms-2 text-success"
                                                        wire:click="applyCoupon"></i>
                                                    @endif

                                                </div>
                                                @if(session()->has('error'))
                                                <div class="alert alert-danger">
                                                    {{ session('error') }}
                                                </div>
                                                @endif
                                                <div class="mb-4 d-flex justify-content-between">
                                                    @if($discountAmount > 0)
                                                    <p class="text-success"> -${{
                                                        number_format($discountAmount, 2) }}</p>
                                                    @endif
                                                    @if($appliedCoupon)
                                                    <i wire:click="removeCoupon()"
                                                        class="cursor-pointer text-danger fas fa-trash"></i>
                                                    @endif
                                                </div>


                                            </div>

                                        </td>
                                    </tr>


                                    <!-- Total -->
                                    <tr>

                                        <td class="py-5">
                                            <p class="py-3 mb-0 text-dark text-uppercase">TOTAL</p>
                                        </td>
                                        <td class="py-5"></td>
                                        <td class="py-5"></td>
                                        <td class="py-5">
                                            <div class="py-3 border-bottom border-top">
                                                <p class="mb-0 text-dark">${{ number_format($total, 2) }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>



                        {{--
                        <!-- Mètodos de pago -->
                        <div class="py-3 text-center row g-4 align-items-center justify-content-center border-bottom">
                            <div class="col-12">
                                <div class="my-3 form-check text-start">
                                    <input type="checkbox" class="border-0 form-check-input bg-primary" id="Transfer-1"
                                        wire:model="paymentMethod" value="Transfer">
                                    <label class="form-check-label" for="Transfer-1">Direct Bank Transfer</label>
                                </div>
                                <div class="my-3 form-check text-start">
                                    <input type="checkbox" class="border-0 form-check-input bg-primary" id="Payments-1"
                                        wire:model="paymentMethod" value="Payments">
                                    <label class="form-check-label" for="Payments-1">Check Payments</label>
                                </div>
                                <div class="my-3 form-check text-start">
                                    <input type="checkbox" class="border-0 form-check-input bg-primary" id="Delivery-1"
                                        wire:model="paymentMethod" value="Delivery">
                                    <label class="form-check-label" for="Delivery-1">Cash On Delivery</label>
                                </div>
                                <div class="my-3 form-check text-start">
                                    <input type="checkbox" class="border-0 form-check-input bg-primary" id="Paypal-1"
                                        wire:model="paymentMethod" value="Paypal">
                                    <label class="form-check-label" for="Paypal-1">Paypal</label>
                                </div>
                            </div>
                        </div> --}}

                        <!-- Botón de finalizar pedido -->
                        <div class="pt-4 text-center row g-4 align-items-center justify-content-center">
                            <button type="submit"
                                class="px-4 py-3 btn border-secondary text-uppercase w-100 text-primary">
                                Place Order
                            </button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
    <!-- Checkout Page End -->
</div>