<!-- Quick View Modal Box Start -->
<div class="modal fade theme-modal view-modal" id="view" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header p-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row g-sm-4 g-2">
                    <div class="col-lg-6">
                        <div class="slider-image text-center p-3 bg-light rounded">
                            <img id="qvImage" src="{{ asset('assets/images/vegetable/product/1.png') }}"
                                class="img-fluid blur-up lazyload" style="max-height: 380px; object-fit: contain;" alt="Product Image">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="right-sidebar-modal">
                            <h4 class="title-name mb-2" id="qvName">Product Name</h4>
                            <h4 class="price theme-color mb-3" id="qvPrice">$0.00</h4>
                            
                            <div class="product-rating mb-3">
                                <ul class="rating d-flex align-items-center gap-1 list-unstyled mb-0">
                                    <li><i data-feather="star" class="fill text-warning"></i></li>
                                    <li><i data-feather="star" class="fill text-warning"></i></li>
                                    <li><i data-feather="star" class="fill text-warning"></i></li>
                                    <li><i data-feather="star" class="fill text-warning"></i></li>
                                    <li><i data-feather="star" class="fill text-warning"></i></li>
                                </ul>
                                <span class="ms-2 text-muted" id="qvStockText">In Stock</span>
                            </div>

                            <div class="product-detail mb-3">
                                <h4 class="mb-1">Product Details :</h4>
                                <p class="text-content" id="qvDescription">Fresh and high quality product from our store.</p>
                            </div>

                            <ul class="brand-list list-unstyled mb-3">
                                <li class="mb-2">
                                    <div class="brand-box d-flex gap-2">
                                        <h5 class="text-muted mb-0">Category:</h5>
                                        <h6 class="mb-0 fw-semibold" id="qvCategory">General</h6>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="brand-box d-flex gap-2">
                                        <h5 class="text-muted mb-0">SKU / Code:</h5>
                                        <h6 class="mb-0 fw-semibold" id="qvSku">N/A</h6>
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="brand-box d-flex gap-2">
                                        <h5 class="text-muted mb-0">Unit / Pack:</h5>
                                        <h6 class="mb-0 fw-semibold" id="qvUnit">1 Unit</h6>
                                    </div>
                                </li>
                            </ul>

                            <form id="qvCartForm" method="POST" action="" class="mb-3">
                                @csrf
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="qty-box" style="max-width: 140px;">
                                        <div class="input-group">
                                            <button type="button" class="btn qty-left-minus" data-type="minus" aria-label="Decrease quantity">
                                                <i class="fa-solid fa-minus"></i>
                                            </button>
                                            <input class="form-control input-number qty-input text-center" type="text"
                                                id="qvQuantity" name="quantity" value="1" min="1" max="100">
                                            <button type="button" class="btn qty-right-plus" data-type="plus" aria-label="Increase quantity">
                                                <i class="fa-solid fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-button d-flex flex-wrap gap-2">
                                    <button type="submit" class="btn btn-md add-cart-button text-white theme-bg-color fw-semibold">
                                        <i class="fa-solid fa-cart-shopping me-1"></i> Add To Cart
                                    </button>
                                    <a id="qvDetailsLink" href="#" class="btn btn-outline-secondary btn-md fw-semibold">
                                        View Full Details <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Quick View Modal Box End -->
