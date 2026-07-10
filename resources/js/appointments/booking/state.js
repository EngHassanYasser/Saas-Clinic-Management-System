export default function (serverData = {}) {
    return {
        // ======================
        // State
        // ======================
        specialties: serverData.specialties ?? [],
        services:serverData.services ?? [],
        currencSection: 1,
        specialitySection: 1,
        serviceSection: 2,
        clinicSection: 3,
        doctorSection: 4,
        dateTimeSection: 5,

        specialtyId: "",
        serviceId: "",
        clinicId: "",
        doctorId: "",
        openSpecialties: false,
        openServices :false,
        selected: {
            specialty: null,
            service: null,
            clinic: null,
            doctor: null,
            date: null,
            slot: null,
        },

        slots: [],
        slotsLoading: false,
        submitting: false,

        toast: {
            show: false,
            message: "",
        },
    };
}
