export default {
    openCreate() {
        this.editMode = false;

        this.form = {
            id: null,
            medicalServiceId: null,
            doctorId: null,
            description: "",
            price: 0,
        };

        this.showModal = true;
    },

    openEdit(item) {
        this.editMode = true;

        this.form = {
            id: item.id,
            medicalServiceId: item.medicalServiceId,
            doctorId: item.doctorId,
            description: item.description,
            price: item.price,
        };
        this.showModal = true;
    }
};
