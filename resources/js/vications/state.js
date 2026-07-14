export default function (serverData = {}) {
    return {
        vications: serverData.vications ?? [],
        doctors: serverData.doctors ?? [],
        search: "",
        status: "",
        month: "",
        showVacationModal: false,
        showDeleteModal: false,
        selectedVacation: null,
        mode:"",
    };
}
