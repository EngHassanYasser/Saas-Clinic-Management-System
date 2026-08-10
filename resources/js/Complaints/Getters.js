export default {
    get filteredComplaintts() {
        return this.complaintts.filter((c) => {
            const matchSearch =
                !this.filters.search ||
                c.patient.includes(this.filters.search) ||
                c.subject.includes(this.filters.search);

            const matchStatus =
                !this.filters.status || c.status === this.filters.status;

            const matchPriority =
                !this.filters.priority || c.priority === this.filters.priority;

            return matchSearch && matchStatus && matchPriority;
        });
    },
    get filteredPatients() {
        if (this.query.trim() === "") return [];

        return this.patients.filter((patient) =>
            patient.name.toLowerCase().includes(this.query.toLowerCase()),
        );
    },

    get totalComplaintts() {
        return this.complaintts.length;
    },
};
