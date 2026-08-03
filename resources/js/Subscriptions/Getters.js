export default {
    get filtered() {

        return this.subscriptions.filter(item => {

            const matchSearch =
                !this.search ||

                item.clinic.toLowerCase().includes(this.search.toLowerCase()) ||

                item.plan.toLowerCase().includes(this.search.toLowerCase());

            const matchStatus =
                !this.statusFilter ||

                this.getStatus(item) === this.statusFilter;

            const matchPlan =
                !this.planFilter ||

                item.plan === this.planFilter;

            return (
                matchSearch &&
                matchStatus &&
                matchPlan
            );

        });

    }
}