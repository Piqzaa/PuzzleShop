/**
 * custome-puzzle.js
 * Gère le flow complet de la page puzzle personnalisé :
 * Upload → Crop (Cropper.js) → Choix variation → AJAX add-to-cart
 *
 * Dépend de cpzData (wp_localize_script) et de Cropper.js (CDN)
 *   cpzData.ajaxUrl   — admin-ajax.php
 *   cpzData.nonce     — nonce cpz_upload_nonce
 *   cpzData.productId — ID produit WC (151)
 *   cpzData.cartUrl   — URL du panier
 */

(function () {
  "use strict";

  // ─── Config ────────────────────────────────────────────────────
  const MAX_FILE_SIZE_MB = 20;
  const ALLOWED_TYPES = ["image/jpeg", "image/png", "image/webp"];

  const ASPECT_MAP = {
    153: 10 / 8,
    154: 14 / 11,
    155: 20 / 16,
  };

  // ─── Refs DOM ──────────────────────────────────────────────────
  const dropzone = document.getElementById("cpz-dropzone");
  const fileInput = document.getElementById("cpz-file-input");
  const uploadError = document.getElementById("cpz-upload-error");
  const addError = document.getElementById("cpz-add-error");
  const cropperImage = document.getElementById("cpz-cropper-image");
  const btnReupload = document.getElementById("cpz-btn-reupload");
  const btnToOptions = document.getElementById("cpz-btn-to-options");
  const btnBackPreview = document.getElementById("cpz-btn-back-preview");
  const btnToCart = document.getElementById("cpz-btn-to-cart");
  const btnRestart = document.getElementById("cpz-btn-restart");
  const variantCards = document.querySelectorAll(".cpz-variant");
  const stepperItems = document.querySelectorAll(".cpz-stepper__item");

  // ─── State ─────────────────────────────────────────────────────
  let state = {
    file: null,
    imageDataUrl: null,
    variationId: null,
  };

  let cropper = null;

  // ─── Init ──────────────────────────────────────────────────────
  function init() {
    if (!dropzone || !fileInput || !cropperImage) {
      console.warn("[cpz] Éléments DOM manquants, abort init.");
      return;
    }

    bindUpload();
    bindNavigation();
    bindVariants();
    initDefaultVariant();
  }

  // ═══════════════════════════════════════════════════════════════
  //  UPLOAD
  // ═══════════════════════════════════════════════════════════════

  function bindUpload() {
    fileInput.addEventListener("change", function (e) {
      const file = e.target.files[0];
      if (file) handleFile(file);
    });

    dropzone.addEventListener("dragover", function (e) {
      e.preventDefault();
      dropzone.classList.add("is-dragover");
    });

    dropzone.addEventListener("dragleave", function () {
      dropzone.classList.remove("is-dragover");
    });

    dropzone.addEventListener("drop", function (e) {
      e.preventDefault();
      dropzone.classList.remove("is-dragover");
      const file = e.dataTransfer.files[0];
      if (file) handleFile(file);
    });

    dropzone.addEventListener("click", function (e) {
      if (e.target.closest("label[for='cpz-file-input']")) return;
      fileInput.click();
    });

    dropzone.addEventListener("keydown", function (e) {
      if (e.key === "Enter" || e.key === " ") {
        e.preventDefault();
        fileInput.click();
      }
    });
  }

  function handleFile(file) {
    clearError(uploadError);

    if (!ALLOWED_TYPES.includes(file.type)) {
      showError(uploadError, "Format non supporté. Utilisez JPG, PNG ou WebP.");
      return;
    }

    const sizeMB = file.size / (1024 * 1024);
    if (sizeMB > MAX_FILE_SIZE_MB) {
      showError(
        uploadError,
        `Fichier trop lourd (${sizeMB.toFixed(1)} Mo). Maximum : ${MAX_FILE_SIZE_MB} Mo.`,
      );
      return;
    }

    state.file = file;

    const reader = new FileReader();
    reader.onload = function (e) {
      state.imageDataUrl = e.target.result;
      goToStep(2);
      initCropper(state.imageDataUrl);
    };
    reader.readAsDataURL(file);
  }

  // ═══════════════════════════════════════════════════════════════
  //  CROPPER.JS
  // ═══════════════════════════════════════════════════════════════

  function getCurrentAspectRatio() {
    return ASPECT_MAP[state.variationId] || 10 / 8;
  }

  function initCropper(dataUrl) {
    destroyCropper();
    cropperImage.src = dataUrl;
    cropper = new Cropper(cropperImage, {
      aspectRatio: getCurrentAspectRatio(),
      viewMode: 2,
      autoCropArea: 1,
      background: false,
      responsive: true,
    });
  }

  function destroyCropper() {
    if (cropper) {
      cropper.destroy();
      cropper = null;
    }
  }

  function updateCropperAspectRatio() {
    if (cropper) {
      cropper.setAspectRatio(getCurrentAspectRatio());
    }
  }

  function getCroppedBlob() {
    if (!cropper || !state.imageDataUrl) return null;

    const data = cropper.getData();

    return new Promise(function (resolve) {
      const img = new Image();
      img.onload = function () {
        const cvs = document.createElement("canvas");
        cvs.width = data.width;
        cvs.height = data.height;
        const ctx = cvs.getContext("2d");
        ctx.drawImage(img, data.x, data.y, data.width, data.height, 0, 0, data.width, data.height);
        const mime = state.file && state.file.type === "image/png" ? "image/png" : "image/jpeg";
        cvs.toBlob(resolve, mime, mime === "image/jpeg" ? 0.92 : undefined);
      };
      img.src = state.imageDataUrl;
    });
  }

  // ═══════════════════════════════════════════════════════════════
  //  VARIATIONS
  // ═══════════════════════════════════════════════════════════════

  function bindVariants() {
    variantCards.forEach(function (card) {
      card.addEventListener("click", function () {
        selectVariant(card);
      });

      const radio = card.querySelector(".cpz-variant__radio");
      if (radio) {
        radio.addEventListener("change", function () {
          selectVariant(card);
        });
      }
    });
  }

  function selectVariant(card) {
    variantCards.forEach(function (c) {
      c.classList.remove("is-selected");
      const r = c.querySelector(".cpz-variant__radio");
      if (r) r.checked = false;
    });

    card.classList.add("is-selected");
    const radio = card.querySelector(".cpz-variant__radio");
    if (radio) {
      radio.checked = true;
      state.variationId = radio.value;
    }

    updateCropperAspectRatio();
  }

  function initDefaultVariant() {
    const firstCard = document.querySelector(".cpz-variant");
    if (firstCard) selectVariant(firstCard);
  }

  // ═══════════════════════════════════════════════════════════════
  //  NAVIGATION STEPPER
  // ═══════════════════════════════════════════════════════════════

  function bindNavigation() {
    if (btnReupload)
      btnReupload.addEventListener("click", function () {
        if (fileInput) fileInput.value = "";
        goToStep(1);
      });
    if (btnToOptions)
      btnToOptions.addEventListener("click", function () {
        goToStep(3);
      });
    if (btnBackPreview)
      btnBackPreview.addEventListener("click", function () {
        goToStep(2);
      });
    if (btnToCart) btnToCart.addEventListener("click", handleAddToCart);
    if (btnRestart) btnRestart.addEventListener("click", resetFlow);
  }

  function goToStep(stepNum) {
    document.querySelectorAll(".cpz-step").forEach(function (el) {
      el.classList.remove("is-current");
      el.hidden = true;
    });

    const target = document.querySelector(`.cpz-step[data-step="${stepNum}"]`);
    if (target) {
      target.classList.add("is-current");
      target.hidden = false;
    }

    updateStepper(stepNum);

    const page = document.getElementById("custom-puzzle-page");
    if (page) {
      page.scrollIntoView({ behavior: "smooth", block: "start" });
    }
  }

  function updateStepper(activeStep) {
    stepperItems.forEach(function (item) {
      const step = parseInt(item.dataset.step, 10);
      item.classList.remove("is-active", "is-done");

      if (step === activeStep) {
        item.classList.add("is-active");
      } else if (step < activeStep) {
        item.classList.add("is-done");
      }
    });
  }

  // ═══════════════════════════════════════════════════════════════
  //  ADD TO CART — AJAX
  // ═══════════════════════════════════════════════════════════════

  async function handleAddToCart() {
    clearError(addError);

    if (!state.variationId) {
      showError(addError, "Veuillez sélectionner un format.");
      return;
    }

    if (!state.file) {
      showError(addError, "Aucune photo sélectionnée.");
      return;
    }

    btnToCart.disabled = true;
    btnToCart.textContent = "Envoi en cours…";

    const blob = await getCroppedBlob();
    if (!blob) {
      showError(addError, "Erreur de recadrage. Réessayez.");
      btnToCart.disabled = false;
      btnToCart.textContent = "Ajouter au panier";
      return;
    }

    const formData = new FormData();
    formData.append("action", "cpz_upload_and_add_to_cart");
    formData.append("nonce", cpzData.nonce);
    formData.append("product_id", cpzData.productId);
    formData.append("variation_id", state.variationId);
    formData.append("puzzle_image", blob, "puzzle.jpg");

    fetch(cpzData.ajaxUrl, {
      method: "POST",
      body: formData,
    })
      .then(function (res) {
        return res.json().then(function (data) {
          if (!data.success) {
            throw new Error(
              data.data && data.data.message
                ? data.data.message
                : "Erreur " + res.status,
            );
          }
          return data;
        });
      })
      .then(function (data) {
        goToStep(4);
        const step4 = document.querySelector('.cpz-stepper__item[data-step="4"]');
        if (step4) step4.classList.add("is-done");
        if (typeof jQuery !== "undefined") {
          jQuery(document.body).trigger("wc_fragment_refresh");
        }
      })
      .catch(function (err) {
        console.error("[cpz] add-to-cart error:", err);
        showError(addError, err.message || "Erreur de connexion. Veuillez réessayer.");
      })
      .finally(function () {
        btnToCart.disabled = false;
        btnToCart.textContent = "Ajouter au panier";
      });
  }

  // ═══════════════════════════════════════════════════════════════
  //  RESET
  // ═══════════════════════════════════════════════════════════════

  function resetFlow() {
    destroyCropper();
    state = {
      file: null,
      imageDataUrl: null,
      variationId: null,
    };

    if (fileInput) fileInput.value = "";
    initDefaultVariant();
    goToStep(1);
  }

  // ═══════════════════════════════════════════════════════════════
  //  UTILS
  // ═══════════════════════════════════════════════════════════════

  function showError(el, msg) {
    if (el) el.textContent = msg;
  }

  function clearError(el) {
    if (el) el.textContent = "";
  }

  // ─── Boot ──────────────────────────────────────────────────────
  document.addEventListener("DOMContentLoaded", init);
})();
