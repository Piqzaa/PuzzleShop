(function () {
  "use strict";

  function updatePrice(input) {
    const form = input.closest("form.cart");
    if (!form) return;

    const qty = parseInt(input.value, 10);
    if (isNaN(qty) || qty < 1) return;

    const detail = form.closest(".product-detail");
    if (!detail) return;

    const varPrice = detail.querySelector(
      ".woocommerce-variation-price .woocommerce-Price-amount"
    );
    const simplePrice = detail.querySelector(
      ".price .woocommerce-Price-amount"
    );

    const target = varPrice || simplePrice;
    if (!target) return;

    if (!target.dataset.unitPrice) {
      const raw = target.textContent.trim().replace(/\s/g, "");
      const match = raw.match(/[\d.,]+/);
      if (!match) return;
      const num = parseFloat(match[0].replace(",", "."));
      if (isNaN(num) || num === 0) return;
      target.dataset.unitPrice = num;
    }

    const unit = parseFloat(target.dataset.unitPrice);
    if (isNaN(unit)) return;

    const total = (unit * qty).toFixed(2).replace(".", ",");
    const symbol = target.textContent.trim().replace(/[\d.,\s]/g, "").trim() || "€";

    const bdi = target.querySelector("bdi");
    const text = total + " " + symbol;
    if (bdi) {
      bdi.textContent = text;
    } else {
      target.textContent = text;
    }
  }

  function triggerChange(input) {
    input.dispatchEvent(new Event("change", { bubbles: true }));
    input.dispatchEvent(new Event("input", { bubbles: true }));
    if (typeof jQuery !== "undefined") {
      jQuery(input).trigger("change");
    }
  }

  document.addEventListener("click", function (e) {
    const btn = e.target.closest(".qty-btn");
    if (!btn) return;

    const quantity = btn.closest(".quantity");
    if (!quantity) return;

    const input = quantity.querySelector("input.qty");
    if (!input) return;

    let current = parseInt(input.value, 10);
    if (isNaN(current)) current = 0;

    let min = parseInt(input.getAttribute("min"), 10);
    if (isNaN(min)) min = 1;

    let max = parseInt(input.getAttribute("max"), 10);
    if (isNaN(max)) max = 0;

    let step = parseInt(input.getAttribute("step"), 10);
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

  if (typeof jQuery !== "undefined") {
    jQuery(document).on("found_variation", "form.cart", function () {
      const input = this.querySelector(".quantity input.qty");
      if (input) updatePrice(input);
    });

    jQuery(document).on("reset_data", "form.cart", function () {
      const detail = this.closest(".product-detail");
      if (!detail) return;
      detail.querySelectorAll("[data-unit-price]").forEach(function (el) {
        delete el.dataset.unitPrice;
      });
    });
  }
})();
