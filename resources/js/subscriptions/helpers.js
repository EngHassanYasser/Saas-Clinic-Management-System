export default {
    getStatus(subscription) {
        const now = new Date();

        const end = new Date(subscription.end);

        const diff = (end - now) / (1000 * 60 * 60 * 24);

        if (end < now) return "expired";

        if (diff <= 7) return "expiring";

        return "active";
    },

    statusLabel(status) {
        return (
            {
                active: "نشط",

                expired: "منتهي",

                expiring: "قريب الانتهاء",
            }[status] ?? ""
        );
    },

    badgeClass(status) {
        switch (status) {
            case this.statuses.ACTIVE:
                return "text-green-600 bg-green-50";

            case this.statuses.CANCELLED:
                return "text-amber-600 bg-amber-50";

            case this.statuses.EXPIRED:
                return "text-red-600 bg-red-50";

            default:
                return "";
        }
    },

    countByStatus(status) {
        return this.subscriptions.filter(
            (subscription) => this.getStatus(subscription) === status,
        ).length;
    },

    resetForm() {
        this.form = {
            clinic: "",

            plan: "",

            price: "",

            start: "",

            end: "",
        };
    },
};
