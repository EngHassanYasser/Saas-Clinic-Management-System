export default {
    deleteService(id) {
        if (!confirm("متأكد؟")) return;
        fetch(`/clinic/services/${id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": this.getToken(),
                Accept: "application/json",
            },
        })
            .then(async (res) => {
                const data = await res.json().catch(() => ({}));

                if (!res.ok || !data.success) {
                    throw new Error(data.message || "Delete failed");
                }

                return data;
            })
            .then(() => {
                this.clinicServices = this.clinicServices.filter(
                    (s) => s.id !== id,
                );
            })
            .catch((err) => {
                console.error(err);
                alert("حصل خطأ أثناء الحذف");
            });
    },
};
