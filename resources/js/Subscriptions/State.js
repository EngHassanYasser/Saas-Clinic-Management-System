export default function (serverData = {}) {
    return {
        subscriptions: serverData.subscriptions ?? [],
        plans:serverData.plans ??[],
        stats:serverData.stats ?? [],
        clinics:serverData.clinics ?? [],
        statuses:serverData.statuses ?? [],
        search: "",

        mode:"add",
        statusFilter: "",
        planFilter: "",

        showModal: false,

        editId: null,

        formError: "",

        form: {
            id: null,
            startAt:null,
            endAt:null,
            status:null,
            price:null,

            plan: {
                id:null,
                name:null,
                monthlyPrice:null,
            },
            clinic: {
                id:null,
                name:null
            }
        },
    };
}
