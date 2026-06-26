(function () {
  "use strict";

  function showToast() {
    const existing = document.querySelector(".bestsellers-toast");
    if (existing) existing.remove();

    const toast = document.createElement("div");
    toast.className = "bestsellers-toast";
    toast.textContent = "Produit ajout\u00e9 au panier";
    document.body.appendChild(toast);

    requestAnimationFrame(function () {
      toast.classList.add("bestsellers-toast--visible");
    });

    setTimeout(function () {
      toast.classList.remove("bestsellers-toast--visible");
      setTimeout(function () {
        toast.remove();
      }, 300);
    }, 2500);
  }

  document.addEventListener("change", function (e) {
    const select = e.target.closest(".card__size-select");
    if (!select) return;

    const card = select.closest(".card");
    if (!card) return;

    const option = select.options[select.selectedIndex];
    const priceEl = card.querySelector(".card__price");
    const addBtn = card.querySelector(".card__add-to-cart");

    if (priceEl && option && option.getAttribute("data-price")) {
      priceEl.textContent = option.getAttribute("data-price");
    }

    if (addBtn && option) {
      addBtn.setAttribute("data-variation-id", option.value);
    }
  });

  document.addEventListener("click", function (e) {
    const btn = e.target.closest(".card__add-to-cart");
    if (!btn) return;

    e.preventDefault();

    const productId = btn.getAttribute("data-product-id");
    const variationId = btn.getAttribute("data-variation-id");

    if (!productId) return;

    btn.classList.add("card__add-to-cart--loading");

    const formData = new FormData();
    formData.append("action", "bestseller_add_to_cart");
    formData.append("product_id", productId);
    formData.append("variation_id", variationId);
    formData.append("security", mypetpuzzle_ajax.nonce);

    fetch(mypetpuzzle_ajax.ajax_url, {
      method: "POST",
      credentials: "same-origin",
      body: formData,
    })
      .then(function (r) {
        if (!r.ok) throw new Error("Network error");
        return r.json();
      })
      .then(function (data) {
        btn.classList.remove("card__add-to-cart--loading");
        if (data.success) {
          btn.classList.add("card__add-to-cart--added");
          showToast();

          if (typeof wc_cart_fragments_params !== "undefined") {
            jQuery(document.body).trigger("wc_fragment_refresh");
          }

          setTimeout(function () {
            btn.classList.remove("card__add-to-cart--added");
          }, 2000);
        } else {
          btn.classList.add("card__add-to-cart--error");
        }
      })
      .catch(function () {
        btn.classList.remove("card__add-to-cart--loading");
        btn.classList.add("card__add-to-cart--error");
      });
  });
})();
