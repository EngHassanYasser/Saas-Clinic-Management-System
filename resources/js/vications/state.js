export default function (serverData = {}) {
    return {
        vications: serverData.vications ?? [],
        doctors: serverData.doctors ?? [],
        editVication: null,
        search: "",
        status: "",
        month: "",
        showVacationModal: false,
        showDeleteModal: false,
        selectedVacation: null,
    };
}
