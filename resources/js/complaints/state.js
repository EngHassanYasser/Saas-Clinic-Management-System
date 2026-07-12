export default function (serverData = {}) {
    return {
        complaints: serverData.complaints ?? [],
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
