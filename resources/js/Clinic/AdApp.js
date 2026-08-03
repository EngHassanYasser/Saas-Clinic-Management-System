document.addEventListener("alpine:init", () => {
    Alpine.data("AdApp", () => ({
        // ===== DATA =====
        ads: [
            {
                id: 1,
                title: "خصم 30% على الكشف",
                desc: "عرض خاص لفترة محدودة على جميع التخصصات",
                status: "active",
            },
            {
                id: 2,
                title: "فحص مجاني للأطفال",
                desc: "يوم مفتوح للكشف المجاني للأطفال أقل من 10 سنوات",
                status: "inactive",
            },
        ],
        nextId: 3,

        // ===== FILTER =====
        activeFilter: "all",

        // ===== MODALS =====
        showModal: false,
        showDelete: false,
        editId: null,
        deleteTarget: null,
        formError: false,

        form: { title: "", desc: "", status: "active" },

        // ===== TOAST =====
        toast: {
            show: false,
            msg: "",
            icon: "fa-circle-check",
            bg: "bg-gray-800",
        },
        _toastTimer: null,

        // ===== COMPUTED =====
        get filtered() {
            if (this.activeFilter === "all") return this.ads;
            return this.ads.filter((a) => a.status === this.activeFilter);
        },

        // ===== ACTIONS =====
        openAdd() {
            this.editId = null;
            this.formError = false;
            this.form = { title: "", desc: "", status: "active" };
            this.showModal = true;
        },

        openEdit(ad) {
            this.editId = ad.id;
            this.formError = false;
            this.form = { ...ad };
            this.showModal = true;
        },

        save() {
            if (!this.form.title.trim() || !this.form.desc.trim()) {
                this.formError = true;
                return;
            }
            this.formError = false;

            if (this.editId) {
                const idx = this.ads.findIndex((a) => a.id === this.editId);
                this.ads[idx] = { ...this.ads[idx], ...this.form };
                this.showToast(
                    "تم تعديل الإعلان بنجاح",
                    "fa-pen-to-square",
                    "bg-amber-500",
                );
            } else {
                this.ads.unshift({ ...this.form, id: this.nextId++ });
                this.showToast(
                    "تمت إضافة الإعلان بنجاح",
                    "fa-circle-check",
                    "bg-green-600",
                );
            }

            this.showModal = false;
        },

        openDelete(ad) {
            this.deleteTarget = ad;
            this.showDelete = true;
        },

        confirmDelete() {
            this.ads = this.ads.filter((a) => a.id !== this.deleteTarget.id);
            this.showDelete = false;
            this.showToast("تم حذف الإعلان بنجاح", "fa-trash", "bg-red-500");
        },

        showToast(msg, icon = "fa-circle-check", bg = "bg-gray-800") {
            this.toast = { show: true, msg, icon, bg };
            if (this._toastTimer) clearTimeout(this._toastTimer);
            this._toastTimer = setTimeout(() => {
                this.toast.show = false;
            }, 3000);
        },
    }));
});
