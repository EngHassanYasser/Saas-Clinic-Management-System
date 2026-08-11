export default {
    avatarClass(name) {
        return this.avatarColors[name.charCodeAt(0) % this.avatarColors.length];
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
    openAdd() {
        this.formError = false;
        this.mode = "add";
        this.showModal = true;
    },
    openEdit(c) {
        this.mode="update";
        this.form = { ...c };
        console.log(this.form);
        this.formError = false;
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
                joinedAt: today,
            });
            this.showToast(
                "تمت إضافة العيادة بنجاح",
                "fa-circle-check",
                "bg-green-600",
            );
        }

        this.showModal = false;
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
};
