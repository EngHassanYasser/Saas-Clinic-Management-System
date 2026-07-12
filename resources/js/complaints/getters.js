export default{
    get filteredComplaints() {
        return this.complaints.filter(c => {

            const matchSearch =
                !this.filters.search ||
                c.patient.includes(this.filters.search) ||
                c.subject.includes(this.filters.search);

            const matchStatus =
                !this.filters.status ||
                c.status === this.filters.status;

            const matchPriority =
                !this.filters.priority ||
                c.priority === this.filters.priority;

            return matchSearch && matchStatus && matchPriority;
        });
    },

    get totalComplaints() {
        return this.complaints.length;
    }
};