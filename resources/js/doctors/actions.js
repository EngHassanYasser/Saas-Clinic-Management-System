export default {
    openEdite(item) {
        this.form = {
            id: item.id,
            image: item.image,
            name: item.name,
            phone: item.phone,
            email: item.email,
            speciality_id: item.speciality?.id ?? null,
        };
        console.log(this.form);
        this.showEditModal = true;
    },
};
