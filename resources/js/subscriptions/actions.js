export default {
    openAdd() {
        this.editId = null;

        this.formError = "";

        this.form = {
            clinic: "",
            plan: "",
            price: "",
            start: "",
            end: "",
        };

        this.showModal = true;
    },

    openEdit(item) {
        this.editId = item.id;

        this.formError = "";

        this.form = {
            ...item,
        };

        this.showModal = true;
    },

    closeModal() {
        this.showModal = false;

        this.editId = null;

        this.formError = "";
    },

    save() {
        const error = this.validateForm();

        if (error) {
            this.formError = error;

            return;
        }

        this.formError = "";

        if (this.editId) {
            const index = this.subscriptions.findIndex(
                (item) => item.id === this.editId,
            );

            if (index !== -1) {
                this.subscriptions[index] = {
                    ...this.form,
                    id: this.editId,
                    price: Number(this.form.price),
                };
            }
        } else {
            this.subscriptions.push({
                ...this.form,

                id: Date.now(),

                price: Number(this.form.price),
            });
        }

        this.closeModal();
    },

    deleteItem(id) {
        if (!confirm("هل أنت متأكد من الحذف؟")) return;

        this.subscriptions = this.subscriptions.filter(
            (item) => item.id !== id,
        );
    },

    renew(id) {
        const subscription = this.subscriptions.find((item) => item.id === id);

        if (!subscription) return;

        const newEnd = new Date();

        newEnd.setFullYear(newEnd.getFullYear() + 1);

        subscription.end = newEnd.toISOString().split("T")[0];
    },
};
