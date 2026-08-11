export default {
    editComplaint(complaintt) {
        this.currentComplaint = complaintt;
        this.mode='update';
        this.showModel=true;
    },
     addComplaint() {
        this.currentComplaint = null;
        this.mode='add';
        this.showModel=true;
    },
    confirmDelete(complaintt) {
        this.deleteComplaint = complaintt;
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
};
