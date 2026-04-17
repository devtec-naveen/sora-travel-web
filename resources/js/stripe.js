(function () {
    "use strict";

    let stripe = null;
    let elements = null;
    let cardElement = null;

    function ensureStripe() {
        if (stripe) return true;

        const key = window.stripePublishableKey;
        if (!key) {
            console.error("[Stripe] window.stripePublishableKey is not set.");
            return false;
        }

        if (typeof window.Stripe === "undefined") {
            console.error("[Stripe] Stripe.js not loaded yet.");
            return false;
        }

        stripe = window.Stripe(key);
        elements = stripe.elements();
        console.log("[Stripe] Initialized.");
        return true;
    }

    function destroyCard() {
        if (cardElement) {
            try {
                cardElement.unmount();
            } catch (_) {}
            try {
                cardElement.destroy();
            } catch (_) {}
            cardElement = null;
            console.log("[Stripe] Card element destroyed.");
        }
    }

    function mountCard(retryCount) {
        retryCount = retryCount || 0;

        if (!ensureStripe()) return;

        const cardEl = document.getElementById("card-element");
        if (!cardEl) {
            console.warn("[Stripe] #card-element not found in DOM.");
            return;
        }

        if (cardEl.offsetWidth === 0 || cardEl.offsetHeight === 0) {
            if (retryCount < 20) {
                console.log(
                    "[Stripe] Waiting for dimensions... attempt " +
                        (retryCount + 1),
                );
                setTimeout(function () {
                    mountCard(retryCount + 1);
                }, 100);
            } else {
                console.error(
                    "[Stripe] #card-element never got visible dimensions. Check modal CSS.",
                );
            }
            return;
        }

        if (cardElement) {
            console.log("[Stripe] Already mounted, skipping.");
            return;
        }

        cardElement = elements.create("card", {
            hidePostalCode: true,
            style: {
                base: {
                    fontSize: "15px",
                    color: "#1e293b",
                    fontFamily: "inherit",
                    "::placeholder": { color: "#94a3b8" },
                },
                invalid: {
                    color: "#ef4444",
                },
            },
        });

        cardElement.mount("#card-element");

        const errEl = document.getElementById("card-errors");
        if (errEl) {
            cardElement.on("change", function (e) {
                errEl.textContent = e.error ? e.error.message : "";
            });
        }

        console.log("[Stripe] Card element mounted successfully.");
    }

    function mountCardAfterModalOpen() {
        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                mountCard(0);
            });
        });
    }

    async function handleSaveCard() {
        if (!stripe || !cardElement) {
            console.error("[Stripe] Not initialised — open the modal first.");
            return;
        }

        const btn = document.getElementById("saveCardBtn");
        const errEl = document.getElementById("card-errors");

        if (!btn) return;

        btn.disabled = true;
        btn.textContent = "Processing…";

        const { paymentMethod, error } = await stripe.createPaymentMethod({
            type: "card",
            card: cardElement,
        });

        btn.disabled = false;
        btn.textContent = "Save Card";

        if (error) {
            if (errEl) errEl.textContent = error.message;
            console.error("[Stripe] createPaymentMethod error:", error);
            return;
        }

        if (errEl) errEl.textContent = "";

        Livewire.dispatch("stripePaymentMethod", {
            paymentMethodId: paymentMethod.id,
        });

        cardElement.clear();
    }

    document.addEventListener("click", function (e) {
        const btn =
            e.target.id === "saveCardBtn"
                ? e.target
                : e.target.closest("#saveCardBtn");
        if (btn) {
            e.preventDefault();
            handleSaveCard();
        }
    });

    document.addEventListener("livewire:init", function () {
        ensureStripe();

        Livewire.on("open-modal", function (data) {
            if (data.id === "add_card_modal") {
                mountCardAfterModalOpen();
            }
        });

        Livewire.on("close-modal", function (data) {
            if (data.id === "add_card_modal") {
                destroyCard();

                const errEl = document.getElementById("card-errors");
                if (errEl) errEl.textContent = "";

                const btn = document.getElementById("saveCardBtn");
                if (btn) {
                    btn.disabled = false;
                    btn.textContent = "Save Card";
                }
            }
        });
    });
})();
