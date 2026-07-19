import { getAvailableSlots } from "./api";
export default {
    handelAvailbleSlots(currentAppointment) {
     this.availableSlots = getAvailableSlots(this.currentAppointment);
    },

    openReschedule(appt) {
        this.currentAppointment = { ...appt };
        this.handelAvailbleSlots(this.currentAppointment),
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
    },

    onDoctorChange() {
        this.selected.doctor =
            this.doctors.find((d) => d.id == this.doctorId) || null;

        this.selected.date = null;
        this.selected.slot = null;
        this.slots = [];

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
