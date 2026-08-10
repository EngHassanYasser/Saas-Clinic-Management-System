export default {
    openCreate() {
        this.editMode = false;

        this.form = {
            id: null,
            medicalService_id: null,
            doctor_id: null,
            description: "",
            price: 0,
        };

        this.showModal = true;
    },

    openEdit(item) {
        this.editMode = true;

        this.form = {
            id: item.id,
            medicalService_id: item.medicalService_id,
            doctor_id: item.doctor_id,
            description: item.description,
            price: item.price,
        };
        this.showModal = true;
    }
};
