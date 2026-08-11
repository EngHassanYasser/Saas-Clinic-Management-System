export default {
    get filteredDoctors() {
        const q = this.search.toLowerCase();

        return this.doctors.filter((d) => {
            const matchSearch =
                d.name.toLowerCase().includes(q) ||
                d.specialty.name.toLowerCase().includes(q);

            const matchSpecialty =
                this.specialty.name === "" ||
                d.specialty.name === this.specialty.name;

            const matchStatus =
                this.status === "" ||
                (this.status === "active" && d.active) ||
                (this.status === "inactive" && !d.active);

            return matchSearch && matchSpecialty && matchStatus;
        });
    },

    generateSlots() {
        const start = document.querySelector("[name=workStart]").value;
        const end = document.querySelector("[name=workEnd]").value;
        const bStart = document.querySelector("[name=breakStart]").value;
        const bEnd = document.querySelector("[name=breakEnd]").value;
        const duration =
            parseInt(document.querySelector("[name=sessionDuration]").value) ||
            30;

        if (!start || !end) return;

        const toMins = (t) => {
            const [h, m] = t.split(":").map(Number);
            return h * 60 + m;
        };
        const toTime = (m) =>
            `${String(Math.floor(m / 60)).padStart(2, "0")}:${String(m % 60).padStart(2, "0")}`;

        let result = [],
            cur = toMins(start);
        const endM = toMins(end);
        const bs = bStart ? toMins(bStart) : null;
        const be = bEnd ? toMins(bEnd) : null;

        while (cur + duration <= endM) {
            if (bs && be && cur >= bs && cur < be) {
                cur = be;
                continue;
            }
            result.push(toTime(cur));
            cur += duration;
        }
        this.slots = result;
    },
};
