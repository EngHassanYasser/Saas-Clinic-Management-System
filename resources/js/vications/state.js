export default function (serverData = {}) {
    return {
        vications: serverData.vications ?? {},

        search: "",
        status: "",
        month: "",

        showVacationModal: false,
        showDeleteModal: false,

        selectedVacation: null,

        form: {
            doctor_id: "",
            reason: "",
            start_date: "",
            end_date: "",
            notes: "",
        },

        doctors: [],
    };
}