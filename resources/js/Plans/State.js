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
            monthlyPrice: "",
            monthlyAppointmentsLimit: 0,
            maxDoctors: 0,
            status: null,
        },
    };
}
