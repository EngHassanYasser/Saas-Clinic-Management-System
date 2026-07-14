export default function (serverData = {}) {
    return {
        vications: serverData.vications ?? [],
        doctors: serverData.doctors ?? [],
        stats:serverData.stats ?? [],
        search: "",
        status: "",
        month: "",
        showVacationModal: false,
        showDeleteModal: false,
        selectedVacation: null,
        mode:"",

    };
}
