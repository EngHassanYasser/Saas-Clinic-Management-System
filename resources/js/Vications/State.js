export default function (serverData = {}) {
    return {
        vacations: serverData.vacations ?? [],
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
