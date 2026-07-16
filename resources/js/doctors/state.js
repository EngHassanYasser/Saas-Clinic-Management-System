export default function (serverData = {}) {
    return {
        workDays: [],
        isActive: true,
        imagePreview: null,
        slots: [],
        search: "",
        specialty: "",
        status: "",
        showEditModal: false,
        imagePreview: null,
        doctors: serverData.doctors ?? [],
        specialities: serverData.specialities ?? [],
        currentDoctor: null,
        mode: "add",
        showModel: false,
    };
}
