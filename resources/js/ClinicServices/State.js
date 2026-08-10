export default function (serverData = {}) {
    return {
        showModal: false,
        editMode: false,

        serviceDropdownOpen: false,
        doctorDropdownOpen: false,
        serviceCatalogs: serverData.serviceCatalogs,
        doctors: serverData.doctors,
        clinicServices: serverData.clinicServices,

        filters: {
            doctorId: "",
            search: "",
        },

        form: {
            id: null,
            medicalService_id: null,
            doctor_id: null,
            description: "",
            price: 0,
        },
    };
}
