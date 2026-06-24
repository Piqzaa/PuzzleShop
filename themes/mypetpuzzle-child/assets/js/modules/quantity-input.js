(function () {
  "use strict";

  function updatePrice(input) {
    var form = input.closest("form.cart");
    if (!form) return;

    var qty = parseInt(input.value, 10);
    if (isNaN(qty) || qty < 1) return;

    var detail = form.closest(".product-detail");
    if (!detail) return;

    // For variable products, variation price takes priority
    var varPrice = detail.querySelector(
      ".woocommerce-variation-price .woocommerce-Price-amount"
    );
    var simplePrice = detail.querySelector(
      ".price .woocommerce-Price-amount"
    );

    var target = varPrice || simplePrice;
    if (!target) return;

    // Store unit price on first call
    if (!target.dataset.unitPrice) {
      var raw = target.textContent.trim().replace(/\s/g, "");
      var match = raw.match(/[\d.,]+/);
      if (!match) return;
      var num = parseFloat(match[0].replace(",", "."));
      if (isNaN(num) || num === 0) return;
      target.dataset.unitPrice = num;
    }

    var unit = parseFloat(target.dataset.unitPrice);
    if (isNaN(unit)) return;

    var total = (unit * qty).toFixed(2).replace(".", ",");
    var symbol = target.textContent.trim().replace(/[\d.,\s]/g, "").trim() || "€";

    var bdi = target.querySelector("bdi");
    var html = total + " " + symbol;
    if (bdi) {
      bdi.innerHTML = html;
    } else {
      target.innerHTML = html;
    }
  }

  function triggerChange(input) {
    input.dispatchEvent(new Event("change", { bubbles: true }));
    input.dispatchEvent(new Event("input", { bubbles: true }));
    if (typeof jQuery !== "undefined") {
      jQuery(input).trigger("change");
    }
  }

  // +/- buttons
  document.addEventListener("click", function (e) {
    var btn = e.target.closest(".qty-btn");
    if (!btn) return;

    var quantity = btn.closest(".quantity");
    if (!quantity) return;

    var input = quantity.querySelector("input.qty");
    if (!input) return;

    var current = parseInt(input.value, 10);
    if (isNaN(current)) current = 0;

    var min = parseInt(input.getAttribute("min"), 10);
    if (isNaN(min)) min = 1;

    var max = parseInt(input.getAttribute("max"), 10);
    if (isNaN(max)) max = 0;

    var step = parseInt(input.getAttribute("step"), 10);
    if (isNaN(step)) step = 1;

    if (btn.classList.contains("qty-btn--minus")) {
      if (current > min) {
        input.value = current - step;
        triggerChange(input);
        updatePrice(input);
      }
    } else if (btn.classList.contains("qty-btn--plus")) {
      if (!max || current < max) {
        input.value = current + step;
        triggerChange(input);
        updatePrice(input);
      }
    }
  });

  // Recalculate when WooCommerce variation is found / reset
  if (typeof jQuery !== "undefined") {
    jQuery(document).on("found_variation", "form.cart", function () {
      var input = this.querySelector(".quantity input.qty");
      if (input) updatePrice(input);
    });

    jQuery(document).on("reset_data", "form.cart", function () {
      var detail = this.closest(".product-detail");
      if (!detail) return;
      // Clear stored unit prices so they re-extract from the default price
      detail.querySelectorAll("[data-unit-price]").forEach(function (el) {
        delete el.dataset.unitPrice;
      });
    });
  }
})();
