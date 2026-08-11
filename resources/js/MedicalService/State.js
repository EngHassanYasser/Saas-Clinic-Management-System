export default function (serverData = {}) {
    return {
        showModal: false,
        editMode: false,

        serviceDropdownOpen: false,
        doctorDropdownOpen: false,
        medicalService: serverData.medicalService,
        doctors: serverData.doctors,
        clinicServices: serverData.clinicServices,

        filters: {
            doctorId: "",
            search: "",
        },

        form: {
            id: null,
            medicalServiceId: null,
            doctorId: null,
            description: "",
            price: 0,
        },
    };
}
