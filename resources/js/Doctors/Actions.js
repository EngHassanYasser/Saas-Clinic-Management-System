export default {
    openEdite(doctor) {
        this.selectedSpeciality = { ...doctor.speciality };
        this.currentDoctor = { ...doctor };
        this.mode = "update";
        this.showModel = true;
    },
    openAdd() {
        this.currentDoctor = null;
        this.selectedSpeciality = null;
        this.mode = "add";
        this.showModel = true;
    },
    imageUploader() {
        return {
            imagePreview: null,

            previewImage(event) {
                const file = event.target.files[0];

                if (!file) return;

                if (!file.type.startsWith("image/")) {
                    alert("اختر صورة صحيحة");
                    event.target.value = "";
                    return;
                }

                if (file.size > 2 * 1024 * 1024) {
                    alert("الحد الأقصى لحجم الصورة 2MB");
                    event.target.value = "";
                    return;
                }

                this.imagePreview = URL.createObjectURL(file);
            },
        };
    },
};
