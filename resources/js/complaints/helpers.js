import { statusMap, priorityMap } from "./constants";

export default {
    status(status) {
        return statusMap[status];
    },

    priority(priority) {
        return priorityMap[priority];
    },

    formatDate(date) {
        return new Date(date).toLocaleDateString("en-GB");
    },
};
