(function () {
  "use strict";

  document.body.addEventListener("updated_checkout", function () {
    var stripe = document.querySelector(".wc-stripe-save-source");
    if (stripe) stripe.remove();
  });
})();
