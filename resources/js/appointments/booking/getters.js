export default {

    get currentStep() {
            if (this.selected.slot) return 5;
            if (this.selected.date) return 5;
            if (this.selected.doctor) return 4;
            if (this.selected.clinic) return 3;
            if (this.selected.specialty) return 2;

            return 1;
        },

        get filteredClinics() {
            if (!this.selected.specialty) return [];

            return this.clinics
        },
        get filterdServices() {
            return this.services.filter(
                (s) =>s.speciality_id  == this.selected.specialty.id
            );
        },

        get filteredDoctors() {
            if (!this.selected.clinic) return [];

            return this.doctors.filter(
                (d) => d.clinic_id === this.selected.clinic.id,
            );
        },

        get availableDates() {
            const dates = [];

            const today = new Date();

            for (let i = 0; i < 14; i++) {
                const d = new Date(today);

                d.setDate(today.getDate() + i);

                dates.push({
                    dateStr: d.toISOString().split("T")[0],

                    dayName: this.arabicDays[d.getDay()],

                    dayNumber: d.getDate(),

                    monthName: this.arabicMonths[d.getMonth()],

                    fullLabel: `${this.arabicDays[d.getDay()]}، ${d.getDate()} ${this.arabicMonths[d.getMonth()]}`,

                    isFriday: d.getDay() === 5,
                });
            }

            return dates;
        },

        get isReady() {
            return (
                this.selected.specialty &&
                this.selected.clinic &&
                this.selected.doctor &&
                this.selected.date &&
                this.selected.slot
            );
        },
}