export default function (serverData) {
    return {
        // ===== DATA =====
        clinics: serverData.clinics,
        nextId: 5,
        perPage: 10,
        currentPage: 1,

        // ===== FILTERS =====
        search: "",
        filterStatus: "",
        filterPlan: "",

        // ===== MODALS =====
        showModal: false,
        showDelete: false,
        showView: false,

        editId: null,
        deleteTarget: null,
        viewTarget: null,
        formError: false,

        form: {
            name: "",
            email: "",
            city: "",
            status: "نشط",
            plan: "Basic",
        },

        // ===== TOAST =====
        toast: {
            show: false,
            msg: "",
            icon: "fa-circle-check",
            bg: "bg-gray-800",
        },
        _toastTimer: null,
    };
}
