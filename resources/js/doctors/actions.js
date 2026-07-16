export default {
    openEdite(doctor) {
        this.currentDoctor=doctor;
        this.mode = "update";
        this.showModel = true;
        console.log(this.currentDoctor,this.mode,this.showModel);
    },
    openAdd() {
        this.form = {};
        this.mode = "add";
        this.showModel = true;
    },
};
