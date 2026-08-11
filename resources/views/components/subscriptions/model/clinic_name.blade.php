<div x-data="{
    open: false,
    selected: null,
 }" class="relative w-full">
    <input x-model="form.clinic.id" type="hidden" name="clinicId" :value="selected?.id">
    <button type="button" @click="open = !open"
        class="w-full border rounded-xl p-3 text-right flex justify-between items-center">
        <span x-text="selected?.name ?? 'اختر العيادة'"></span>
        <i x-show="mode == 'add'" class="fa-solid fa-chevron-down"></i>
    </button>
    <div x-show="open && mode == 'add'" @click.outside="open = false" x-transition
        class="absolute z-50 mt-2 w-full bg-white border rounded-xl shadow-lg max-h-60 overflow-y-auto">
        <template x-for="clinic in clinics" :key="clinic.id">
            <button type="button"
                @click="
                    form.clinic = clinic;
                    selected = clinic;
                    open = false;
                "
                class="w-full text-right px-4 py-3 hover:bg-blue-50" x-text="clinic.name"></button>
        </template>
    </div>
</div>
