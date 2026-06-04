document.addEventListener("alpine:init", () => {
    Alpine.data("clinicsApp", () => ({
        // ===== DATA =====
        clinics: [
            {
                id: 1,
                name: "عيادة النور",
                email: "dr.noor@clinic.com",
                city: "القاهرة",
                status: "نشط",
                plan: "Premium",
                date: "15 يناير 2024",
            },
            {
                id: 2,
                name: "عيادة السلام",
                email: "salam@clinic.com",
                city: "الإسكندرية",
                status: "قيد المراجعة",
                plan: "Basic",
                date: "3 مارس 2024",
            },
            {
                id: 3,
                name: "عيادة الرحمة",
                email: "rahma@clinic.com",
                city: "الجيزة",
                status: "موقوف",
                plan: "Trial",
                date: "20 فبراير 2024",
            },
            {
                id: 4,
                name: "عيادة الشفاء",
                email: "shifa@clinic.com",
                city: "المنصورة",
                status: "نشط",
                plan: "Premium",
                date: "1 أبريل 2024",
            },
        ],
        nextId: 5,
        perPage: 10,
        currentPage: 1,

        // ===== FILTERS =====
        search: "",
        filterStatus: "",
        filterPlan: "",

        // ===== MODALS =====
        showModal: false,
        showDelete: false,
        showView: false,

        editId: null,
        deleteTarget: null,
        viewTarget: null,
        formError: false,

        form: {
            name: "",
            email: "",
            city: "",
            status: "نشط",
            plan: "Basic",
        },

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
            const q = this.search.toLowerCase();
            return this.clinics.filter(
                (c) =>
                    (!q ||
                        c.name.toLowerCase().includes(q) ||
                        c.city.toLowerCase().includes(q) ||
                        c.email.toLowerCase().includes(q)) &&
                    (!this.filterStatus || c.status === this.filterStatus) &&
                    (!this.filterPlan || c.plan === this.filterPlan),
            );
        },

        get totalPages() {
            return Math.max(1, Math.ceil(this.filtered.length / this.perPage));
        },

        get paginated() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filtered.slice(start, start + this.perPage);
        },

        get paginationInfo() {
            const total = this.filtered.length;
            if (total === 0) return "لا توجد نتائج";
            const from = (this.currentPage - 1) * this.perPage + 1;
            const to = Math.min(this.currentPage * this.perPage, total);
            return `عرض ${from} - ${to} من أصل ${total} عيادة`;
        },

        // ===== STYLE HELPERS =====
        avatarColors: [
            "bg-blue-100 text-blue-600",
            "bg-emerald-100 text-emerald-600",
            "bg-violet-100 text-violet-600",
            "bg-amber-100 text-amber-600",
            "bg-red-100 text-red-500",
            "bg-pink-100 text-pink-600",
        ],

        avatarClass(name) {
            return this.avatarColors[
                name.charCodeAt(0) % this.avatarColors.length
            ];
        },

        avatarLetter(name) {
            return name.replace("عيادة", "").trim()[0] || name[0];
        },

        statusBadgeClass(s) {
            return (
                {
                    نشط: "bg-green-50 text-green-700",
                    موقوف: "bg-red-50 text-red-600",
                    "قيد المراجعة": "bg-amber-50 text-amber-700",
                }[s] ?? "bg-gray-100 text-gray-500"
            );
        },

        statusDotClass(s) {
            return (
                {
                    نشط: "bg-green-500",
                    موقوف: "bg-red-400",
                    "قيد المراجعة": "bg-amber-500",
                }[s] ?? "bg-gray-400"
            );
        },

        statusTextClass(s) {
            return (
                {
                    نشط: "text-green-600",
                    موقوف: "text-red-500",
                    "قيد المراجعة": "text-amber-600",
                }[s] ?? "text-gray-600"
            );
        },

        planBadgeClass(p) {
            return (
                {
                    Premium: "bg-violet-50 text-violet-700",
                    Basic: "bg-blue-50 text-blue-700",
                    Trial: "bg-gray-100 text-gray-500",
                }[p] ?? "bg-gray-100 text-gray-500"
            );
        },

        planIcon(p) {
            return (
                {
                    Premium: "fa-crown",
                    Basic: "fa-box",
                    Trial: "fa-flask",
                }[p] ?? "fa-tag"
            );
        },

        // ===== ACTIONS =====
        openAdd() {
            this.editId = null;
            this.formError = false;
            this.form = {
                name: "",
                email: "",
                city: "",
                status: "نشط",
                plan: "Basic",
            };
            this.showModal = true;
        },

        openEdit(c) {
            this.editId = c.id;
            this.formError = false;
            this.form = {
                ...c,
            };
            this.showModal = true;
        },

        save() {
            if (
                !this.form.name.trim() ||
                !this.form.email.trim() ||
                !this.form.city.trim()
            ) {
                this.formError = true;
                return;
            }
            this.formError = false;

            if (this.editId) {
                const idx = this.clinics.findIndex((x) => x.id === this.editId);
                this.clinics[idx] = {
                    ...this.clinics[idx],
                    ...this.form,
                };
                this.showToast(
                    "تم تعديل بيانات العيادة بنجاح",
                    "fa-pen-to-square",
                    "bg-amber-500",
                );
            } else {
                const today = new Date().toLocaleDateString("ar-EG", {
                    day: "numeric",
                    month: "long",
                    year: "numeric",
                });
                this.clinics.unshift({
                    ...this.form,
                    id: this.nextId++,
                    date: today,
                });
                this.showToast(
                    "تمت إضافة العيادة بنجاح",
                    "fa-circle-check",
                    "bg-green-600",
                );
            }

            this.showModal = false;
        },

        openView(c) {
            this.viewTarget = c;
            this.showView = true;
        },

        openDelete(c) {
            this.deleteTarget = c;
            this.showDelete = true;
        },

        confirmDelete() {
            this.clinics = this.clinics.filter(
                (x) => x.id !== this.deleteTarget.id,
            );
            this.showDelete = false;
            this.showToast("تم حذف العيادة بنجاح", "fa-trash", "bg-red-500");
        },

        toggleStatus(id) {
            const c = this.clinics.find((x) => x.id === id);
            c.status = c.status === "موقوف" ? "نشط" : "موقوف";
            const on = c.status === "نشط";
            this.showToast(
                on ? "تم تفعيل العيادة" : "تم إيقاف العيادة",
                on ? "fa-circle-check" : "fa-ban",
                on ? "bg-green-600" : "bg-red-500",
            );
        },

        showToast(msg, icon = "fa-circle-check", bg = "bg-gray-800") {
            this.toast = {
                show: true,
                msg,
                icon,
                bg,
            };
            if (this._toastTimer) clearTimeout(this._toastTimer);
            this._toastTimer = setTimeout(() => {
                this.toast.show = false;
            }, 3000);
        },
    }));
});
