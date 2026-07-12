export default {
    formatTime(h, m) {
        const period = h >= 12 ? "م" : "ص";

        let h12 = h % 12;

        if (h12 === 0) h12 = 12;

        return `${h12}:${String(m).padStart(2, "0")} ${period}`;
    },

    showToast(message) {
        this.toast.message = message;

        this.toast.show = true;

        setTimeout(() => {
            this.toast.show = false;
        }, 2500);
    },
    badgeClass(status) {
        const map = {
            confirmed: "badge-confirmed",
            pending: "badge-pending",
            cancelled: "badge-cancelled",
            completed: "badge-completed",
            noShow: "badge-noshow",
        };

        return map[status] ?? "badge-pending";
    },

    statusIcon(status) {
        const map = {
            confirmed: "fa-check-circle",
            pending: "fa-hourglass-half",
            cancelled: "fa-times-circle",
            completed: "fa-check-double",
            noShow: "fa-user-times",
        };

        return map[status] ?? "fa-circle";
    },
};
