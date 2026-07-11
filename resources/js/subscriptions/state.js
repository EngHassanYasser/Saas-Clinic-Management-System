export default function (serverData = {}) {
    return {
        subscriptions: serverData.subscriptions ?? [],

        search: "",

        statusFilter: "",

        planFilter: "",

        showModal: false,

        editId: null,

        formError: "",

        form: {
            clinic: "",

            plan: "",

            price: "",

            start: "",

            end: "",
        },
    };
}
