export default function (serverData = {}) {
    return {
        // ======================
        // State
        // ======================
        specialties: serverData.specialties ?? [],
        services: serverData.services ?? [],
        appointments: serverData.appointments ?? [],
        stats: serverData.stats ?? [],
        availableSlots:[],
        currencSection: 1,
        specialitySection: 1,
        serviceSection: 2,
        clinicSection: 3,
        doctorSection: 4,
        dateTimeSection: 5,
        selectedStatus: "all",

        specialtyId: "",
        serviceId: "",
        clinicId: "",
        doctorId: "",
        openSpecialties: false,
        openServices: false,
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
        showRescheduleModal: false,
        selectedSlot:null,
        currentAppointment:null,
        toast: {
            show: false,
            message: "",
        },
    };
}
