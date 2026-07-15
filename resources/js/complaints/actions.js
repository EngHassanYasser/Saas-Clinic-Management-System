export default {
    editComplain(complaint) {
        this.currentComplaint = complaint;
        this.mode='update';
        this.showModel=true;
    },
     addComplain() {
        this.currentComplaint = null;
        this.mode='add';
        this.showModel=true;
    },


    confirmDelete(complaint) {
        this.deleteComplaint = complaint;
        this.deleteModal = true;
    },

    doDelete() {
        this.complaints = this.complaints.filter(
            (c) => c.id !== this.deleteComplaint.id,
        );

        this.deleteModal = false;
        this.deleteComplaint = null;
    },

    changeStatus(status) {
        this.currentComplaint.status = status;
    },

    sendReply() {
        if (!this.replyText.trim()) return;

        this.changeStatus("resolved");

        this.closeDetails();
    },
    selectPatient(patient) {
        this.query = patient.name;
        this.patientId = patient.id;
        this.open = false;
    },

    typing() {
        this.patientId = "";
        this.open = this.query.length > 0;
    },
};
