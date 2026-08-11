<label>الخطة</label>
    <input x-model="form.plan.id" type="hidden" name="planId">
<select x-model="form.plan.id"
    class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
    <option value="" disabled>اختر الخطة</option>
    <template x-for="plan in plans" :key="plan.id">
        <option :value="plan.id"
            x-text="`${plan.name} - ${plan.monthlyPrice} ج.م / شهر`">
        </option>
    </template>
</select>