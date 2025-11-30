<div>
    <!-- Hero Section -->
    <section class="hero-video hero hero-image">
        {{-- <video class="video-slide" src="{{ asset('videos/hero_vid.mp4') }}" autoplay muted loop></video> --}}
        <x-hero/>
    </section>    

    {{-- <!-- Shop Section -->
    <section class="shop my-5">
        <div class="container py-5">
            <div class="row">
                <!-- Shop Section Title -->
                <div class="shop-heading">
                    <h1 class="font-primary fw-bolder display-4">Shop</h1>
                    <hr>
                </div>
                @foreach ($categories as $category)
                @php
                $isEven = $loop->iteration % 2 === 0;
                @endphp
                <!-- Card 1 -->
                <div class="col-6 col-md-4 col-lg-12 mt-5" wire:key="{{ $category->id }}">
                    <div class="card flex-column flex-lg-row align-items-center bg-secondary border-0">
                        <div class="col-lg-6 {{ $isEven ? 'order-lg-2' : 'order-lg-1' }}">
                            <a wire:navigate href="/products?selected_categories[0]={{ $category->id }}">
                                <img src="{{ Storage::url($category->image) }}" class="img-fluid w-100 card-cat-image"
                                    alt="{{ $category->name }}" />
                            </a>
                        </div>
                        <div class="card-body col-lg-6 order-lg-1">
                            <h2 class="font-primary fw-bolder card-title d-none d-lg-block text-dark">
                                {{ $category->name }}
                            </h2>
                            <div class="card-text d-none d-lg-block">
                                <p class="font-secondary fw-light text-dark">
                                    {{ $category->description }}
                                </p>
                                <a wire:navigate href="/products??selected_categories[0]={{ $category->id }}"
                                    class="font-primary fw-bolder btn btn-lg btn-primary text-light px-5 rounded-0">Shop</a>
                            </div>
                            <div class="d-grid d-md-none">
                                <a wire:navigate href="/products??selected_categories[0]={{ $category->id }}"
                                    class="font-primary fw-light btn btn-outline-primary text-dark">{{ $category->name
                                    }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section> --}}

    @livewire('partials.shop')
    
    <!-- Trending [Horizontal Cards] Swiper Slide Show -->
    <section class="horizontal-cards my-5">
        <div class="container">
            <div class="row">
                <div class="container text-center py-3 text-dark">
                    <h2 class="font-primary fw-bolder display-6">Trending</h2>
                </div>
            </div>

            <!-- Swiper Container -->
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    @foreach ($featuredProducts as $featuredProduct)
                        <!-- Slide 1 -->
                        <div class="swiper-slide" wire:key="fetured-{{ $featuredProduct->id }}">
                            <div class="card trending-card bg-primary text-light border-primary">
                                <div class="row g-0">
                                    <div class="col-6 col-md-5 order-1">
                                        <img src="{{ Storage::url($featuredProduct->images[0]) }}"
                                            class="card-img img-fluid rounded-start" alt="Pendant" />
                                    </div>
                                    <div class="col-6 col-md-7">
                                        <div class="card-body d-flex flex-column">
                                            <div class="h-100">
                                                <h3 class="font-secondary fw-light card-title text-light">
                                                    {{ $featuredProduct->category->name }}
                                                </h3>
                                                <a href="{{ route('product.detail', $featuredProduct->slug) }}"
                                                    class="text-light text-decoration-none">
                                                    <h2 class="font-primary fw-bolder card-title">
                                                        {{ $featuredProduct->name }}
                                                </a>
                                                </h2>
                                                <p class="font-secondary fw-light card-text">
                                                    {{ $featuredProduct->description }}
                                                </p>
                                                <h4 class="card-title mb-3">
                                                    ৳<strong
                                                        class="font-secondary mx-1">{{ $featuredProduct->price}}</strong>
                                                    </strong>
                                                </h4>
                                                <div>
                                                    <livewire:add-to-cart-button :product_id="$featuredProduct->id" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    {{-- <div class="d-flex justify-content-center align-items-center gap-4 position-relative">
                        <div class="swiper-button-wrapper">
                            <button class="swiper-prev btn mx-2 text-primary" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24">
                                    <path fill="none" stroke="currentColor"
                                        d="M8 5c0 .742-.733 1.85-1.475 2.78c-.954 1.2-2.094 2.247-3.401 3.046C2.144 11.425.956 12 0 12m0 0c.956 0 2.145.575 3.124 1.174c1.307.8 2.447 1.847 3.401 3.045C7.267 17.15 8 18.26 8 19m-8-7h24"
                                        stroke-width="1" />
                                </svg>
                            </button>
                        </div>
                        <div class="swiper-button-wrapper">
                            <button class="swiper-next btn mx-2 text-primary" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"
                                    class="flip-horizontal">
                                    <path fill="none" stroke="currentColor"
                                        d="M8 5c0 .742-.733 1.85-1.475 2.78c-.954 1.2-2.094 2.247-3.401 3.046C2.144 11.425.956 12 0 12m0 0c.956 0 2.145.575 3.124 1.174c1.307.8 2.447 1.847 3.401 3.045C7.267 17.15 8 18.26 8 19m-8-7h24"
                                        stroke-width="1" />
                                </svg>
                            </button>
                        </div>
                    </div> --}}
                </div>
                <div class="row">
                    <div class="container">
                        <div class="d-flex justify-content-center py-3">
                            <button class="btn mx-2 swiper-prev text-primary" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24">
                                    <path fill="none" stroke="currentColor"
                                        d="M8 5c0 .742-.733 1.85-1.475 2.78c-.954 1.2-2.094 2.247-3.401 3.046C2.144 11.425.956 12 0 12m0 0c.956 0 2.145.575 3.124 1.174c1.307.8 2.447 1.847 3.401 3.045C7.267 17.15 8 18.26 8 19m-8-7h24"
                                        stroke-width="1" />
                                </svg>
                            </button>
                            <button class="btn text-primary mx-2" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"
                                    class="flip-horizontal swiper-next">
                                    <path fill="none" stroke="currentColor"
                                        d="M8 5c0 .742-.733 1.85-1.475 2.78c-.954 1.2-2.094 2.247-3.401 3.046C2.144 11.425.956 12 0 12m0 0c.956 0 2.145.575 3.124 1.174c1.307.8 2.447 1.847 3.401 3.045C7.267 17.15 8 18.26 8 19m-8-7h24"
                                        stroke-width="1" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--Carousel Slide Show -->
    <section class="slide-show">
        <div class="row">
            <div class="carousel slide carousel-slide" data-bs-ride="carousel" id="carouselNavigation">
                <div class="carousel-inner">

                    @foreach ($categories as $category)
                        <div class="carousel-item active" wire:key="{{ $category->id }}">
                            <img src="{{ Storage::url($category->image) }}" class="img-fluid w-100" alt="...">
                            <div
                                class="d-none d-md-block carousel-caption d-flex flex-column align-items-center justify-content-center bg-primary bg-opacity-50 text-light mb-5 rounded-3">
                                <h2 class="font-primary fw-bold d-none d-md-block">{{ $category->name }}</h2>
                                <p class="font-secondary d-none d-md-block">
                                    {{ $category->description }}
                                </p>
                                <a wire:navigate class="font-primary btn btn-lg btn-outline-primary text-light rounded-0"
                                    href="/products?selected_categories[0]={{ $category->id }}">Shop
                                    Now
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>


                <button class="carousel-control-prev" type="button" data-bs-target="#carouselNavigation"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselNavigation"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>

                <div class="container">
                    <div class="row">
                        <div class="">
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#carouselNavigation" data-bs-slide-to="0"
                                    class="active" aria-current="true" aria-label="Slide 1"></button>
                                <button type="button" data-bs-target="#carouselNavigation" data-bs-slide-to="1"
                                    aria-label="Slide 2"></button>
                                <button type="button" data-bs-target="#carouselNavigation" data-bs-slide-to="2"
                                    aria-label="Slide 3"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row d-md-block d-md-none">
            <div class="col-sm-12 text-center my-5">
                <a wire:navigate class="font-primary btn btn-lg btn-primary text-light rounded-0"
                    href="{{ route('all.products') }}">Shop
                    Now
                </a>
            </div>
        </div>
    </section>

    <!-- About Section-->
    <section id="about" class="bg-primary text-light py-5">
        <div class="container px-5">
            <div class="row gx-5 justify-content-center">
                <div class="col-xxl-8">
                    <div class="text-center my-5">
                        <h2 class="font-primary fw-bolder display-6 text-light">About Me</h2>
                        <p class="font-secondary fw-light lead fw-light mb-4">
                            My name is Hasan Rakib and I help brands grow.
                        </p>
                        <p class="font-seondary text-light">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit
                            dolorum itaque qui unde quisquam consequatur autem. Eveniet
                            quasi nobis aliquid cumque officiis sed rem iure ipsa!
                            Praesentium ratione atque dolorem?
                        </p>
                        <div class="d-flex justify-content-center fs-2 gap-4">
                            <a class="text-gradient" href="#!"><i class="bi bi-twitter"></i></a>
                            <a class="text-gradient" href="#!"><i class="bi bi-linkedin"></i></a>
                            <a class="text-gradient" href="#!"><i class="bi bi-github"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script src="https://unpkg.com/gsap@3.12.5/dist/gsap.min.js"></script>
        <script src="https://unpkg.com/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
        <script src="https://unpkg.com/swiper@11/swiper-bundle.min.js"></script>
        <script src="{{ asset('js/coverflow.js') }}"></script>
        <script src="{{ asset('js/heroLogo.js') }}"></script>
    @endpush
</div>