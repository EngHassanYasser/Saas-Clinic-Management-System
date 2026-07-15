import constraint from "./constants";

export default {
    status(status) {
        return constraint.statusMap[status];
    },

    priority(priority) {
        return constraint.priorityMap[priority];
    },

    formatDate(date) {
        return new Date(date).toLocaleDateString("en-GB");
    },
};
