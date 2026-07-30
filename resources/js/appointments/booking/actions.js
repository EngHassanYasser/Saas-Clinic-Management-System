import {
    getAvailableSlots,
    getClinicServices,
    getAvailableClinics,
    getAvailableDoctors,
} from "./api";
export default {
    async handelAvailbleSlots(currentAppointment) {
        const response = await getAvailableSlots(currentAppointment);
        this.availableSlots = response;
    },
    async handelAvailableServices(specialityId) {
        const response = await getClinicServices(specialityId);
        this.services = response.data;
    },
    async handleAvailableClinics(specialityId, serviceId) {
        this.clinics = await getAvailableClinics(specialityId, serviceId);
    },
    async handleAvailableDoctors(clinicId, specialityId, serviceId) {
        this.doctors = await getAvailableDoctors(
            clinicId,
            specialityId,
            serviceId,
        );
    },
    openReschedule(appt) {
        this.currentAppointment = { ...appt };
        this.handelAvailbleSlots(this.currentAppointment);
        this.showRescheduleModal = true;
    },
    goToStep(step) {
        this.currencSection = step;
    },
    onSpecialtyChange() {
        this.selected.specialty =
            this.specialties.find((s) => s.id == this.specialtyId) || null;
        this.clinicId = "";
        this.doctorId = "";
        this.serviceId = "";

        this.selected.clinic = null;
        this.selected.doctor = null;
        this.selected.date = null;
        this.selected.slot = null;
        this.selected.service = null;

        this.slots = [];
        this.currencSection = this.serviceSection;
        this.reschedule = null;
        console.log("speciality id", this.selected.specialty.id);

        this.handelAvailableServices(this.selected.specialty.id);
    },

    onServiceChange() {
        this.selected.service =
            this.services.find((s) => s.id == this.serviceId) || null;

        this.clinicId = "";
        this.doctorId = "";

        this.selected.clinic = null;
        this.selected.doctor = null;
        this.selected.date = null;
        this.selected.slot = null;

        this.slots = [];

        this.currencSection = this.clinicSection;
        console.log("service id", this.selected.service.id);
        this.handleAvailableClinics(
            this.selected.specialty.id,
            this.selected.service.id,
        );
    },

    onClinicChange() {
        this.selected.clinic =
            this.clinics.find((c) => c.id == this.clinicId) || null;

        this.doctorId = "";

        this.selected.doctor = null;
        this.selected.date = null;
        this.selected.slot = null;

        this.slots = [];

        this.currencSection = this.doctorSection;
        console.log("clinic id", this.selected.clinic.id);

        this.handleAvailableDoctors(
            this.selected.clinic.id,
            this.selected.specialty.id,
            this.selected.service.id,
        );
    },

    onDoctorChange() {
        this.selected.doctor =
            this.doctors.find((d) => d.id == this.doctorId) || null;

        this.selected.date = null;
        this.selected.slot = null;
        this.slots = [];
        console.log("doctor id", this.selected.doctor.id);

        this.currencSection = this.dateTimeSection;
    },

    selectDate(date) {
        this.selected.date = date;

        this.selected.slot = null;

        this.loadSlots(date.dateStr);
    },

    selectSlot(slot) {
        this.selected.slot = slot;
    },

    submitBooking() {
        if (!this.isReady) return;

        this.submitting = true;

        setTimeout(() => {
            this.showToast("تم الحجز بنجاح ✓");

            this.submitting = false;
        }, 700);
    },
    confirmCancel(id) {
        console.log(id);
        // باقي الكود
    },
};
