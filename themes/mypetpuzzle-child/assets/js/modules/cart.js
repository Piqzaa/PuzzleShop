(function ($) {
    "use strict";

    function debounce(func, wait, immediate) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            const later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }

    const triggerCartUpdate = debounce(function() {
        const $updateBtn = $('[name="update_cart"]');
        if ($updateBtn.length) {
            $updateBtn.trigger('click');
        }
    }, 500);

    function initCartQtyButtons() {
        $(document).off('click', '.qty-btn').on('click', '.qty-btn', function (e) {
            e.preventDefault();

            const $btn = $(this);
            const $qtyContainer = $btn.closest('.quantity');
            const $input = $qtyContainer.find('input.qty');

            if (!$input.length) return;

            const currentVal = parseFloat($input.val()) || 0;
            const max = parseFloat($input.attr('max'));
            const min = parseFloat($input.attr('min')) || 0;
            const step = parseFloat($input.attr('step')) || 1;

            let newVal = currentVal;

            if ($btn.hasClass('qty-btn--plus')) {
                if (isNaN(max) || currentVal < max) {
                    newVal = currentVal + step;
                }
            } else if ($btn.hasClass('qty-btn--minus')) {
                if (currentVal > min) {
                    newVal = currentVal - step;
                }
            }

            if (!isNaN(max) && newVal > max) newVal = max;
            if (newVal < min) newVal = min;

            if (newVal !== currentVal) {
                $input.val(newVal).trigger('change');
                triggerCartUpdate();
            }
        });

        $(document).off('change', 'input.qty').on('change', 'input.qty', function() {
            triggerCartUpdate();
        });
    }

    $(document).on('click', '.cart-page__coupon-toggle-btn', function (e) {
        e.preventDefault();
        const $btn = $(this);
        const isExpanded = $btn.attr('aria-expanded') === 'true';
        $btn.attr('aria-expanded', !isExpanded);
        $btn.closest('.cart-page__coupon-toggle').find('.cart-page__coupon').prop('hidden', isExpanded);
    });

    $(document).ready(function() {
        initCartQtyButtons();
    });

    $(document.body).on('updated_wc_div', function() {
        initCartQtyButtons();

        if ($('.woocommerce-cart-form__contents .cart_item').length === 0) {
            location.reload();
        }
    });

})(jQuery);
