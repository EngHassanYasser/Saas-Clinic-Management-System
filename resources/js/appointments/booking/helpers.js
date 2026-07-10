export default {

    formatTime(h, m) {

        const period = h >= 12 ? 'م' : 'ص';

        let h12 = h % 12;

        if (h12 === 0)
            h12 = 12;

        return `${h12}:${String(m).padStart(2, '0')} ${period}`;

    },

    showToast(message) {

        this.toast.message = message;

        this.toast.show = true;

        setTimeout(() => {

            this.toast.show = false;

        }, 2500);

    }

}