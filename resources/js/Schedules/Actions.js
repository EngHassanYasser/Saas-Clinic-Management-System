export default {
    toggleDay(id) {
        this.selectedDays.includes(id)
            ? (this.selectedDays = this.selectedDays.filter((d) => d !== id))
            : this.selectedDays.push(id);
    },
    openEdit(schedule,doctor) {
        this.selectedDays = (schedule.days ?? []).map(day => day.id);
        this.currentDoctor ={...doctor } ;
        this.editSchedule = { ...schedule };
        this.showModel = true;
        this.editeMode = true;


        console.log(schedule.startTime);
    },
    openAdd() {
        this.addMode = true;
        this.showModel = true;
    },
    openAddModal(doctor) {
        this.currentDoctor = doctor;
        console.log(this.currentDoctor.schedules);
        this.showModel = true;
        this.addMode = true;
    },
};
