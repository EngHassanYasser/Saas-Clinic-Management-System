export default {
    editComplaint(complaintt) {
        this.currentComplaintt = complaintt;
        this.mode='update';
        this.showModel=true;
    },
     addComplaint() {
        this.currentComplaintt = null;
        this.mode='add';
        this.showModel=true;
    },
    confirmDelete(complaintt) {
        this.deleteComplaintt = complaintt;
        this.deleteModal = true;
    },

    doDelete() {
        this.complaintts = this.complaintts.filter(
            (c) => c.id !== this.deleteComplaintt.id,
        );

        this.deleteModal = false;
        this.deleteComplaintt = null;
    },

    changeStatus(status) {
        this.currentComplaintt.status = status;
    },
};
