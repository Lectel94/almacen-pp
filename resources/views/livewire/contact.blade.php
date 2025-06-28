<div>
    <!-- Contact Start -->
    <div class="py-5 container-fluid contact">
        <div class="container py-5">
            <div class="p-5 rounded bg-light">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="mx-auto text-center" style="max-width: 700px;">
                            <h1 class="text-primary">Get in touch</h1>
                            <p class="mb-4">
                                The contact form is currently inactive. Get a functional and working contact form with
                                Ajax & PHP in a few minutes. Just copy and paste the files, add a little code and you're
                                done. <a href="https://htmlcodex.com/contact-form" target="_blank">Download Now</a>.
                            </p>

                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="rounded h-100">
                            <iframe class="rounded w-100" style="height: 400px;"
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d387191.33750346623!2d-73.97968099999999!3d40.6974881!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2sbd!4v1694259649153!5m2!1sen!2sbd"
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <form wire:submit.prevent="send" class="">
                            <input type="text" class="py-3 mb-4 border-0 w-100 form-control" placeholder="Your Name"
                                wire:model="name">
                            <input type="email" class="py-3 mb-4 border-0 w-100 form-control"
                                placeholder="Enter Your Email" wire:model="email">
                            <textarea class="mb-4 border-0 w-100 form-control" rows="5" placeholder="Your Message"
                                wire:model="message"></textarea>
                            <button type="submit"
                                class="py-3 bg-white w-100 btn form-control border-secondary text-primary">Submit</button>
                        </form>
                        @if(session()->has('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif
                    </div>
                    <div class="col-lg-5">
                        <div class="p-4 mb-4 bg-white rounded d-flex">
                            <i class="fas fa-map-marker-alt fa-2x text-primary me-4"></i>
                            <div>
                                <h4>Address</h4>
                                <p class="mb-2">123 Street New York.USA</p>
                            </div>
                        </div>
                        <div class="p-4 mb-4 bg-white rounded d-flex">
                            <i class="fas fa-envelope fa-2x text-primary me-4"></i>
                            <div>
                                <h4>Mail Us</h4>
                                <p class="mb-2">info@example.com</p>
                            </div>
                        </div>
                        <div class="p-4 bg-white rounded d-flex">
                            <i class="fa fa-phone-alt fa-2x text-primary me-4"></i>
                            <div>
                                <h4>Telephone</h4>
                                <p class="mb-2">(+012) 3456 7890</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->
</div>