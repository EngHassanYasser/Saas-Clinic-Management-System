export default {
    get filtered() {
        const q = this.search.toLowerCase();
        return this.clinics.filter(
            (c) =>
                (!q ||
                    c.name.toLowerCase().includes(q) ||
                    c.city.toLowerCase().includes(q) ||
                    c.email.toLowerCase().includes(q)) &&
                (!this.filterStatus || c.status === this.filterStatus) &&
                (!this.filterPlan || c.plan === this.filterPlan),
        );
    },

    get totalPages() {
        return Math.max(1, Math.ceil(this.filtered.length / this.perPage));
    },

    get paginated() {
        const start = (this.currentPage - 1) * this.perPage;
        return this.filtered.slice(start, start + this.perPage);
    },

    get paginationInfo() {
        const total = this.filtered.length;
        if (total === 0) return "لا توجد نتائج";
        const from = (this.currentPage - 1) * this.perPage + 1;
        const to = Math.min(this.currentPage * this.perPage, total);
        return `عرض ${from} - ${to} من أصل ${total} عيادة`;
    },
};
