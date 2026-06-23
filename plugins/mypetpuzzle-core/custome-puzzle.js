/**
 * custom-puzzle.js
 * Gère le flow complet de la page puzzle personnalisé :
 * Upload → Preview canvas → Choix variation → AJAX add-to-cart
 *
 * Dépend de cpzData (wp_localize_script) :
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
  const CANVAS_MAX_W = 680; // largeur max du canvas de preview (px)

  // Grille simulée selon le nb de pièces sélectionné
  const GRID_MAP = {
    120: { cols: 12, rows: 10 },
    252: { cols: 18, rows: 14 },
    500: { cols: 25, rows: 20 },
  };

  // ─── Refs DOM ──────────────────────────────────────────────────
  const dropzone = document.getElementById("cpz-dropzone");
  const fileInput = document.getElementById("cpz-file-input");
  const uploadError = document.getElementById("cpz-upload-error");
  const addError = document.getElementById("cpz-add-error");
  const canvas = document.getElementById("cpz-canvas");
  const ctx = canvas ? canvas.getContext("2d") : null;
  const btnReupload = document.getElementById("cpz-btn-reupload");
  const btnToOptions = document.getElementById("cpz-btn-to-options");
  const btnBackPreview = document.getElementById("cpz-btn-back-preview");
  const btnToCart = document.getElementById("cpz-btn-to-cart");
  const btnRestart = document.getElementById("cpz-btn-restart");
  const variantCards = document.querySelectorAll(".cpz-variant");
  const stepperItems = document.querySelectorAll(".cpz-stepper__item");

  // ─── State ─────────────────────────────────────────────────────
  let state = {
    file: null, // File object
    imageDataUrl: null, // base64 pour le canvas
    variationId: null, // ID variation WC sélectionnée
    pieces: null, // nb de pièces (pour la grille)
  };

  // ─── Init ──────────────────────────────────────────────────────
  function init() {
    if (!dropzone || !fileInput || !canvas) {
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
    // Clic sur la dropzone (délégué à l'input file en absolu)
    fileInput.addEventListener("change", function (e) {
      const file = e.target.files[0];
      if (file) handleFile(file);
    });

    // Drag & drop
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

    // Clic sur la dropzone (hors label)
    dropzone.addEventListener("click", function () {
      fileInput.click();
    });

    // Accessibilité clavier sur la dropzone
    dropzone.addEventListener("keydown", function (e) {
      if (e.key === "Enter" || e.key === " ") {
        e.preventDefault();
        fileInput.click();
      }
    });
  }

  function handleFile(file) {
    clearError(uploadError);

    // Validation type
    if (!ALLOWED_TYPES.includes(file.type)) {
      showError(uploadError, "Format non supporté. Utilisez JPG, PNG ou WebP.");
      return;
    }

    // Validation taille
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
      renderCanvas(state.imageDataUrl);
    };
    reader.readAsDataURL(file);
  }

  // ═══════════════════════════════════════════════════════════════
  //  CANVAS PREVIEW
  // ═══════════════════════════════════════════════════════════════

  function renderCanvas(dataUrl) {
    const img = new Image();
    img.onload = function () {
      // Calcule les dimensions du canvas en respectant le ratio
      const ratio = img.height / img.width;
      const width = Math.min(img.width, CANVAS_MAX_W);
      const height = Math.round(width * ratio);

      canvas.width = width;
      canvas.height = height;

      // Dessine l'image
      ctx.drawImage(img, 0, 0, width, height);

      // Superpose la grille selon la variation sélectionnée
      drawGrid(width, height);
    };
    img.src = dataUrl;
  }

  function drawGrid(width, height) {
    const pieces = state.pieces || "252"; // défaut
    const grid = GRID_MAP[pieces] || GRID_MAP["252"];
    const cellW = width / grid.cols;
    const cellH = height / grid.rows;

    ctx.save();
    ctx.strokeStyle = "rgba(255, 255, 255, 0.55)";
    ctx.lineWidth = 0.8;

    // Lignes verticales
    for (let c = 1; c < grid.cols; c++) {
      ctx.beginPath();
      ctx.moveTo(c * cellW, 0);
      ctx.lineTo(c * cellW, height);
      ctx.stroke();
    }

    // Lignes horizontales
    for (let r = 1; r < grid.rows; r++) {
      ctx.beginPath();
      ctx.moveTo(0, r * cellH);
      ctx.lineTo(width, r * cellH);
      ctx.stroke();
    }

    // Bordure extérieure
    ctx.strokeStyle = "rgba(255, 255, 255, 0.8)";
    ctx.lineWidth = 1.5;
    ctx.strokeRect(0, 0, width, height);

    ctx.restore();
  }

  // Re-render le canvas quand la variation change (grille différente)
  function refreshGrid() {
    if (!state.imageDataUrl) return;
    const img = new Image();
    img.onload = function () {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
      drawGrid(canvas.width, canvas.height);
    };
    img.src = state.imageDataUrl;
  }

  // ═══════════════════════════════════════════════════════════════
  //  VARIATIONS
  // ═══════════════════════════════════════════════════════════════

  function bindVariants() {
    variantCards.forEach(function (card) {
      card.addEventListener("click", function () {
        selectVariant(card);
      });

      // Accessibilité : sélection au clavier via l'input radio natif
      const radio = card.querySelector(".cpz-variant__radio");
      if (radio) {
        radio.addEventListener("change", function () {
          selectVariant(card);
        });
      }
    });
  }

  function selectVariant(card) {
    // Retire la sélection de toutes les cards
    variantCards.forEach(function (c) {
      c.classList.remove("is-selected");
      const r = c.querySelector(".cpz-variant__radio");
      if (r) r.checked = false;
    });

    // Active la card cliquée
    card.classList.add("is-selected");
    const radio = card.querySelector(".cpz-variant__radio");
    if (radio) {
      radio.checked = true;
      state.variationId = radio.value;
      state.pieces = radio.dataset.pieces
        ? radio.dataset.pieces.replace(/[^0-9]/g, "")
        : "252";
    }

    // Refresh la grille si on est déjà sur l'étape preview
    refreshGrid();
  }

  function initDefaultVariant() {
    // Pré-sélectionne la première card au chargement
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
    // Masque toutes les sections
    document.querySelectorAll(".cpz-step").forEach(function (el) {
      el.classList.remove("is-current");
      el.hidden = true;
    });

    // Affiche la section cible
    const target = document.querySelector(`.cpz-step[data-step="${stepNum}"]`);
    if (target) {
      target.classList.add("is-current");
      target.hidden = false;
    }

    // Met à jour le stepper visuel
    updateStepper(stepNum);

    // Scroll en haut de la section
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

  function handleAddToCart() {
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

    // Prépare le FormData pour l'upload AJAX
    const formData = new FormData();
    formData.append("action", "cpz_upload_and_add_to_cart");
    formData.append("nonce", cpzData.nonce);
    formData.append("product_id", cpzData.productId);
    formData.append("variation_id", state.variationId);
    formData.append("puzzle_image", state.file, state.file.name);

    fetch(cpzData.ajaxUrl, {
      method: "POST",
      body: formData,
    })
      .then(function (res) {
        if (!res.ok) throw new Error("Erreur réseau : " + res.status);
        return res.json();
      })
      .then(function (data) {
        if (data.success) {
          goToStep(4);
        } else {
          const msg =
            data.data && data.data.message
              ? data.data.message
              : "Une erreur est survenue. Veuillez réessayer.";
          showError(addError, msg);
        }
      })
      .catch(function (err) {
        console.error("[cpz] add-to-cart error:", err);
        showError(addError, "Erreur de connexion. Veuillez réessayer.");
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
    state = {
      file: null,
      imageDataUrl: null,
      variationId: null,
      pieces: null,
    };

    // Reset input file
    if (fileInput) fileInput.value = "";

    // Reset canvas
    if (ctx) ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Reset variants
    initDefaultVariant();

    // Retour étape 1
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
