// === Spinner Manager ===
const SpinnerManager = {
    originalContent: new Map(),
    show(div) {
        if (!spinners[div]) {
            console.warn(`Spinner "${div}" no está definido en spinners.js`);
            return;
        }
        spinners[div].forEach((selector) => {
            const $el = $(selector);
            if ($el.length === 0) return;
            if (!this.originalContent.has(selector)) {
                this.originalContent.set(selector, $el.html());
            }
            $el.html('<span><i class="fa fa-spinner fa-spin"></i></span>');
        });
        // console.log("SpinnerManager State:", this.originalContent);
    },
    hide(div) {
        if (!spinners[div]) return;
        spinners[div].forEach((selector) => {
            const $el = $(selector);
            if ($el.length === 0) return;
            const original = this.originalContent.get(selector);
            $el.html(original || "");
            this.originalContent.delete(selector);
        });
    },
    clear() {
        this.originalContent.clear();
    },
};
