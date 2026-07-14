export default {
    openModal() {
        this.selectedVacation = null;
        this.showVacationModal = true;
    },

    closeModal() {
        this.showVacationModal = false;
    },

    editVacation(v) {
        this.selectedVacation = { ...v };
        this.showVacationModal = true;
    },

    saveVacation() {},

    confirmDelete(v) {
        this.selectedVacation = v;

        this.showDeleteModal = true;
    },

    closeDeleteModal() {
        this.selectedVacation = null;

        this.showDeleteModal = false;
    },

    deleteVacation() {},
};
