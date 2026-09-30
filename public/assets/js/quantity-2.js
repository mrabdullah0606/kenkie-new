/**=====================
    Quantity 2 js & Storefront Interaction
==========================**/
$(document).ready(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    function updateCartCount(count) {
        let badge = $('.bag-icon .badge-number, .header-wishlist .badge');
        if (count > 0) {
            if (badge.length) {
                badge.text(count).show();
            } else {
                $('.bag-icon').append('<small class="badge-number badge-light">' + count + '</small>');
            }
        } else {
            badge.hide();
        }
    }

    function updateWishlistCount(count) {
        let badge = $('.swap-icon .badge-number, .header-wishlist[href*="wishlist"] .badge');
        if (count > 0) {
            if (badge.length) {
                badge.text(count).show();
            } else {
                $('.swap-icon').append('<small class="badge-number badge-light">' + count + '</small>');
            }
        } else {
            badge.hide();
        }
    }

    // Add to cart button click (product card)
    $(document).off('click', '.addcart-button').on('click', '.addcart-button', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        var $btn = $(this);
        var slug = $btn.data('slug');
        var $cartQty = $btn.siblings('.cart_qty');

        $btn.hide();
        $cartQty.addClass('open').css('display', 'inline-flex');
        $cartQty.find('.qty-input').val('1');

        if (slug) {
            $.ajax({
                url: '/cart/' + slug,
                type: 'POST',
                data: {
                    _token: csrfToken,
                    _method: 'PATCH',
                    quantity: 1
                },
                dataType: 'json',
                success: function (res) {
                    if (res && res.cartCount !== undefined) {
                        updateCartCount(res.cartCount);
                    }
                    if (window.$.notify) {
                        $.notify({ message: 'Item added to cart!' }, { type: 'success', delay: 1000 });
                    }
                }
            });
        }
    });

    // Quantity Plus
    $(document).off('click', '.qty-right-plus').on('click', '.qty-right-plus', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        var $btn = $(this);
        var $input = $btn.siblings('.qty-input');
        var currentVal = parseInt($input.val(), 10) || 1;
        var maxVal = parseInt($input.attr('max'), 10) || 99;
        var $form = $btn.closest('form');
        var slug = $btn.data('slug') || ($form.length ? $form.data('slug') : null);

        if (currentVal < maxVal) {
            var newVal = currentVal + 1;
            $input.val(newVal);

            if (slug) {
                $.ajax({
                    url: '/cart/' + slug,
                    type: 'POST',
                    data: {
                        _token: csrfToken,
                        _method: 'PATCH',
                        quantity: newVal
                    },
                    dataType: 'json',
                    success: function (res) {
                        if (res && res.cartCount !== undefined) {
                            updateCartCount(res.cartCount);
                        }
                        if ($('.cart-table').length) {
                            window.location.reload();
                        }
                    }
                });
            }
        }
    });

    // Quantity Minus
    $(document).off('click', '.qty-left-minus').on('click', '.qty-left-minus', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        var $btn = $(this);
        var $input = $btn.siblings('.qty-input');
        var currentVal = parseInt($input.val(), 10) || 1;
        var $cartQty = $btn.closest('.cart_qty');
        var $form = $btn.closest('form');
        var slug = $btn.data('slug') || ($form.length ? $form.data('slug') : null);

        if (currentVal <= 1) {
            $input.val('0');
            $cartQty.removeClass('open').hide();
            $cartQty.siblings('.addcart-button').show();

            if (slug) {
                $.ajax({
                    url: '/cart/' + slug,
                    type: 'POST',
                    data: {
                        _token: csrfToken,
                        _method: 'DELETE'
                    },
                    dataType: 'json',
                    success: function (res) {
                        if (res && res.cartCount !== undefined) {
                            updateCartCount(res.cartCount);
                        }
                        if ($('.cart-table').length) {
                            window.location.reload();
                        }
                    }
                });
            }
        } else {
            var newVal = currentVal - 1;
            $input.val(newVal);

            if (slug) {
                $.ajax({
                    url: '/cart/' + slug,
                    type: 'POST',
                    data: {
                        _token: csrfToken,
                        _method: 'PATCH',
                        quantity: newVal
                    },
                    dataType: 'json',
                    success: function (res) {
                        if (res && res.cartCount !== undefined) {
                            updateCartCount(res.cartCount);
                        }
                        if ($('.cart-table').length) {
                            window.location.reload();
                        }
                    }
                });
            }
        }
    });

    // Wishlist AJAX toggle
    $(document).off('submit', '.wishlist-form').on('submit', '.wishlist-form', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        var $form = $(this);
        var url = $form.attr('action');
        var data = $form.serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (res) {
                if (res && res.wishlistCount !== undefined) {
                    updateWishlistCount(res.wishlistCount);
                }
                if (res && res.message && window.$.notify) {
                    $.notify({ message: res.message }, { type: 'info', delay: 1000 });
                }
            },
            error: function () {
                $form.off('submit').submit();
            }
        });
    });
});