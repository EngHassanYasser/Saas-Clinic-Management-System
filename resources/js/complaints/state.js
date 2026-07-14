export default function (serverData = {}) {
    return {
        complaints: serverData.complaints ?? [],
        stats:serverData.stats ?? [],
        filters: {
            search: "",
            status: "",
            priority: "",
        },

        currentComplaint: null,
        deleteComplaint: null,

        detailsModal: false,
        deleteModal: false,
    };
}
