export default function (serverData = {}) {
    return {
        complaintts: serverData.complaintts ?? [],
        stats: serverData.stats ?? [],
        doctors:serverData.doctors ?? [],
        showModel:false,
        filters: {
            search: "",
            status: "",
            priority: "",
        },
        query: "",
        patientId: "",
        open: false,
        currentComplaintt: null,
        deleteComplaintt: null,
        detailsModal: false,
        deleteModal: false,
    };
}
