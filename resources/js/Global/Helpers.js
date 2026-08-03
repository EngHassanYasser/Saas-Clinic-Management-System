export default {
    csrfToken: document.querySelector('meta[name="csrf-token"]').content,
    formatDate(date) {
        if (!date) return "";

        return new Date(date).toLocaleDateString("en-GB");
    },
    format12Hour(time) {
        if (!time) return "";

        const today = new Date().toISOString().split("T")[0];

        // اعتبر الوقت UTC
        const utcDate = new Date(`${today}T${time}Z`);

        // اعرضه Local
        return utcDate.toLocaleTimeString([], {
            hour: "numeric",
            minute: "2-digit",
            hour12: true,
        });
    },
};
