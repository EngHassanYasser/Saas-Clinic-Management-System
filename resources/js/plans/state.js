export default function (serverData = {}) {
    return {
        plans: serverData.plans ?? [],
        statuses:serverData.statuses ?? [],
        mode:'add',
        showModal: false,
        editing: false,

        form: {
            id: null,
            name: "",
            monthly_price: "",
            monthly_appointments_limit: 0,
            max_doctors: 0,
            status: null,
        },
    };
}
