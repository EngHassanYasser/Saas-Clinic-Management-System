document.addEventListener("alpine:init", () => {
    Alpine.data("BookingApp1", () => ({
        service: "",
        doctor: "",
        date: "",
        selectedTime: "",
        slots: [],

        get statusText() {
            if (!this.date) return "اختار يوم";
            if (this.slots.length === 0) return "مفيش مواعيد";
            return "اختار ميعاد";
        },

        generateSlots(date) {
            const baseSlots = [
                "09:00",
                "09:30",
                "10:00",
                "10:30",
                "11:00",
                "11:30",
                "12:00",
                "12:30",
                "01:00",
                "01:30",
                "02:00",
            ];
            const seed = new Date(date).getDate();
            return baseSlots.filter((_, i) => (i + seed) % 3 !== 0);
        },

        onDateChange() {
            this.selectedTime = "";
            this.slots = this.date ? this.generateSlots(this.date) : [];
        },

        selectSlot(slot) {
            this.selectedTime = slot;
        },
    }));
});
