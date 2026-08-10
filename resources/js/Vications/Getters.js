export default {

    get filteredVacations() {

        const vacations = this.vacations?.data ?? [];

        return vacations.filter(v => {

            const doctorName = v.doctor?.name?.toLowerCase() ?? "";

            const matchesSearch =
                !this.search ||
                doctorName.includes(this.search.toLowerCase());

            const matchesStatus =
                !this.status ||
                v.status === this.status;

            const matchesMonth =
                !this.month ||
                (v.start_date ?? "").startsWith(this.month);

            return matchesSearch && matchesStatus && matchesMonth;
        });

    },

    get totalVacations() {
        return this.vacations?.total ?? 0;
    },

    get activeCount() {
        return (this.vacations?.data ?? [])
            .filter(v => v.status === "active")
            .length;
    },

    get upcomingCount() {
        return (this.vacations?.data ?? [])
            .filter(v => v.status === "upcoming")
            .length;
    },

    get endedCount() {
        return (this.vacations?.data ?? [])
            .filter(v => v.status === "ended")
            .length;
    }

}