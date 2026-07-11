export default {
    deleteDoctor(id) {
        fetch(`/doctors/${id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN":
                    document.querySelector(`input[name='_token']`).value,
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
                // remove from UI
                this.doctors = this.doctors.filter((d) => d.id !== id);
            })
            .catch((err) => {
                console.error(err);
                alert("حصل خطأ أثناء الحذف");
            });
    },
};
