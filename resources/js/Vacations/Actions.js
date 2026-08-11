export default {
    openModal() {
        this.mode='add';
        this.selectedVacation = null;
        this.showVacationModal = true;
    },

    closeModal() {
        this.showVacationModal = false;
    },

    editVacation(v) {
        this.mode='update';
        this.selectedVacation = { ...v };
        this.showVacationModal = true;
    },

    saveVacation() {},

    closeDeleteModal() {
        this.selectedVacation = null;
        this.showDeleteModal = false;
    },

    deleteVacation() {},
};
