(function ($) {
    "use strict";

    // Debounce function to avoid rapid multiple updates
    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }

    // Function to trigger native WooCommerce cart update via AJAX
    var triggerCartUpdate = debounce(function() {
        var $updateButton = $('.woocommerce-cart-form :input[name="update_cart"]');
        if ($updateButton.length) {
            $updateButton.prop('disabled', false).trigger('click');
        } else {
            $(document.body).trigger('wc_update_cart');
        }
    }, 500); // Wait 500ms after last click before triggering update

    function initCartQtyButtons() {
        // Handle plus and minus button clicks
        $(document).off('click', '.qty-btn').on('click', '.qty-btn', function (e) {
            e.preventDefault();
            
            var $btn = $(this);
            var $qtyContainer = $btn.closest('.quantity');
            var $input = $qtyContainer.find('input.qty');
            
            if (!$input.length) return;
            
            var currentVal = parseFloat($input.val()) || 0;
            var max = parseFloat($input.attr('max'));
            var min = parseFloat($input.attr('min')) || 0;
            var step = parseFloat($input.attr('step')) || 1;
            
            var newVal = currentVal;
            
            if ($btn.hasClass('qty-btn--plus')) {
                if (isNaN(max) || currentVal < max) {
                    newVal = currentVal + step;
                }
            } else if ($btn.hasClass('qty-btn--minus')) {
                if (currentVal > min) {
                    newVal = currentVal - step;
                }
            }
            
            // Respect boundaries
            if (!isNaN(max) && newVal > max) newVal = max;
            if (newVal < min) newVal = min;
            
            // Only update and trigger if value actually changed
            if (newVal !== currentVal) {
                $input.val(newVal).trigger('change');
                
                // Trigger auto-update
                triggerCartUpdate();
            }
        });

        // Trigger auto-update if quantity input is typed in manually
        $(document).off('change', 'input.qty').on('change', 'input.qty', function() {
            triggerCartUpdate();
        });
    }

    // Coupon toggle show/hide
    $(document).on('click', '.cart-page__coupon-toggle-btn', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var isExpanded = $btn.attr('aria-expanded') === 'true';
        $btn.attr('aria-expanded', !isExpanded);
        $btn.closest('.cart-page__coupon-toggle').find('.cart-page__coupon').prop('hidden', isExpanded);
    });

    // Initialize on page load
    $(document).ready(function() {
        initCartQtyButtons();
    });

    // Re-initialize after WooCommerce AJAX updates the cart div
    $(document.body).on('updated_wc_div', function() {
        initCartQtyButtons();

        // If cart is empty after AJAX update, reload to show empty state
        if ($('.woocommerce-cart-form__contents .cart_item').length === 0) {
            location.reload();
        }
    });

})(jQuery);
