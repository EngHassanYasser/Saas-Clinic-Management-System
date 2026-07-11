export default {
    toggleDay(id) {
        this.selectedDays.includes(id)
            ? (this.selectedDays = this.selectedDays.filter((d) => d !== id))
            : this.selectedDays.push(id);
    },
    openEdit(schedule) {
        this.editeMode = true;
        this.editSchedule = { ...schedule };
        this.showModel = true;
    },
    openAdd() {
        this.addMode = true;
        this.showModel = true;
    },
};
