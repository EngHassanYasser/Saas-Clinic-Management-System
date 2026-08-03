export default function (serverData = {}) {
    return {
        workDays: [],
        isActive: true,
        imagePreview: null,
        slots: [],
        search: "",
        specialty: "",
        status: "",
        imagePreview: null,
        doctors: serverData.doctors ?? [],
        specialities: serverData.specialities ?? [],
        stats:serverData.stats,
        selectedSpeciality:null,
        currentDoctor: null,
        mode: "add",
        showModel: false,
    };
}
