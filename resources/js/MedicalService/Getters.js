export default {
    get filteredServices() {
        return this.clinicServices.filter((s) => {
            const matchDoctor = this.filters.doctorId
                ? s.doctorId == this.filters.doctorId
                : true;

            const matchSearch = this.filters.search
                ? (s.description ?? "")
                      .toLowerCase()
                      .includes(this.filters.search.toLowerCase())
                : true;

            return matchDoctor && matchSearch;
        });
    },
};
