export default {

    daysBetween(start, end) {
        const s = new Date(start);
        const e = new Date(end);

        if (e < s) return 0;

        return Math.floor((e - s) / 86400000) + 1;
    },

    statusLabel(status) {
        return {
            active: "جارية",
            upcoming: "قادمة",
            ended: "منتهية",
        }[status] ?? status;
    },

    statusClass(status) {
        return {
            active: "bg-amber-100 text-amber-700",
            upcoming: "bg-blue-100 text-blue-700",
            ended: "bg-gray-100 text-gray-500",
        }[status] ?? "bg-gray-100 text-gray-500";
    },
    formatDate(date) {

    if (!date) return "";

    return new Date(date).toLocaleDateString("en-GB");

},

}