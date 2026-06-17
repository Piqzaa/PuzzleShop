(function () {
  "use strict";

  function fixCheckoutLabels() {
    document.querySelectorAll('label[for="billing_phone"], label[for="shipping_phone"]').forEach(function (label) {
      label.textContent = label.textContent.replace("Phone", "T\u00e9l\u00e9phone");
    });
    document.querySelectorAll('label[for="order_comments"]').forEach(function (label) {
      label.textContent = label.textContent.replace("Order notes", "Notes de commande");
    });
    document.querySelectorAll(".optional").forEach(function (el) {
      el.textContent = "(optionnel)";
    });
    var stripe = document.querySelector(".wc-stripe-save-source");
    if (stripe) stripe.remove();
  }

  fixCheckoutLabels();

  document.body.addEventListener("updated_checkout", fixCheckoutLabels);
})();
