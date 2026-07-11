export default {
    openCreate() {
        this.editMode = false;

        this.form = {
            id: null,
            clinic_service_id: null,
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
            clinic_service_id: item.clinic_service_id,
            doctor_id: item.doctor_id,
            description: item.description,
            price: item.price,
        };
        this.showModal = true;
    },
    getToken() {
        return document.querySelector(`input[name='_token']`).value;
    },
};
