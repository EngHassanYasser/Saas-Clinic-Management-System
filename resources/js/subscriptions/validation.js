export default {

    validateForm() {

        if (!this.form.clinic.trim())
            return "اسم العيادة مطلوب";

        if (!this.form.plan)
            return "الخطة مطلوبة";

        if (!this.form.price)
            return "السعر مطلوب";

        if (!this.form.start)
            return "تاريخ البداية مطلوب";

        if (!this.form.end)
            return "تاريخ الانتهاء مطلوب";

        return null;

    }

};