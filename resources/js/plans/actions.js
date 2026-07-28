export default {
    openCreate() {
        this.resetForm();
        this.mode = "add";
        this.showModal = true;
    },

    openEdit(plan) {
        this.form = {
            id: plan.id,
            name: plan.name,
            monthly_price: plan.monthly_price,
            duration: plan.duration,
            max_doctors: plan.max_doctors,
            monthly_appointments_limit: plan.monthly_appointments_limit,
            status: plan.status,
        };
        this.mode = 'update';
        this.showModal = true;
    },

    closeModal() {
        this.resetForm();
        this.showModal = false;
    },

    resetForm() {
        this.form = {
            id: null,
            name: "",
            monthly_price: "",
            max_doctors: 0,
            monthly_appointments_limit: 0,
            status: "active",
        };
    },
};
