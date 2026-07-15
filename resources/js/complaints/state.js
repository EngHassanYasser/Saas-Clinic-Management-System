export default function (serverData = {}) {
    return {
        complaints: serverData.complaints ?? [],
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
        selectedComplain:null,
        patients: [],
        currentComplaint: null,
        deleteComplaint: null,
        detailsModal: false,
        deleteModal: false,
    };
}
