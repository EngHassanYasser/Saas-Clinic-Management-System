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
            monthlyPrice: plan.monthlyPrice,
            duration: plan.duration,
            maxDoctors: plan.maxDoctors,
            monthlyAppointmentsLimit: plan.monthlyAppointmentsLimit,
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
            monthlyPrice: "",
            maxDoctors: 0,
            monthlyAppointmentsLimit: 0,
            status: "active",
        };
    },
};
